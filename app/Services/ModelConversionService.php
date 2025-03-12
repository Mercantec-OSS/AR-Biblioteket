<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

class ModelConversionService
{
    private $supportedFormats = ['obj', 'stl', 'fbx', 'ply', '3ds', 'gltf', 'glb'];
    private static $npmGlobalPath = null;

    private function getNpmGlobalPath()
    {
        if (self::$npmGlobalPath === null) {
            $npmPath = new Process(['npm', 'root', '-g']);
            $npmPath->run();
            
            if (!$npmPath->isSuccessful()) {
                throw new \RuntimeException('Could not determine npm global path');
            }
            
            self::$npmGlobalPath = trim($npmPath->getOutput());
        }
        
        return self::$npmGlobalPath;
    }

    public function convertToGlb($uploadedFile, $mtlFile = null, $textureFile = null)
    {
        $tempDir = storage_path('app/temp/' . Str::random(10));
        
        try {
            mkdir($tempDir, 0777, true);
            
            $originalExtension = strtolower($uploadedFile->getClientOriginalExtension());
            
            if ($originalExtension === 'glb') {
                return $uploadedFile;
            }

            // Bevar de originale filnavne
            $objFileName = $uploadedFile->getClientOriginalName();
            $uploadedFile->move($tempDir, $objFileName);
            $originalPath = $tempDir . '/' . $objFileName;
            
            // Hvis der er en MTL fil, bevar dens originale navn
            if ($mtlFile) {
                $mtlFileName = pathinfo($objFileName, PATHINFO_FILENAME) . '.mtl';
                $mtlFile->move($tempDir, $mtlFileName);
                
                // Hvis der er en teksturfil
                if ($textureFile) {
                    // Læs MTL filen for at finde det forventede teksturnavn
                    $mtlContent = file_get_contents($tempDir . '/' . $mtlFileName);
                    
                    // Find tekstur reference i MTL filen
                    if (preg_match('/map_Kd\s+(.+\.(bmp|png|jpg|jpeg|tga|dds))/i', $mtlContent, $matches)) {
                        $textureFileName = basename($matches[1]);
                        // Gem teksturfilen med det navn som MTL filen forventer
                        $textureFile->move($tempDir, $textureFileName);
                    } else {
                        // Hvis ingen reference findes, brug original filnavn
                        $textureFileName = $textureFile->getClientOriginalName();
                        $textureFile->move($tempDir, $textureFileName);
                        
                        // Opdater MTL filen med det korrekte teksturnavn
                        $mtlContent = preg_replace(
                            '/(map_Kd\s+)(.+\.(bmp|png|jpg|jpeg|tga|dds))/i',
                            '$1' . $textureFileName,
                            $mtlContent
                        );
                        file_put_contents($tempDir . '/' . $mtlFileName, $mtlContent);
                    }
                }
            }
            
            // Output path for converted file
            $outputPath = $tempDir . '/converted.glb';
            
            if ($originalExtension !== 'gltf') {
                $this->convertToGltf($originalPath, $tempDir . '/temp.gltf', $originalExtension);
                $originalPath = $tempDir . '/temp.gltf';
            }

            $process = new Process([
                'gltf-pipeline',
                '-i', basename($originalPath),
                '-o', basename($outputPath),
                '--draco.compressionLevel=4',
                '--draco.quantizePositionBits=14',
                '--draco.quantizeNormalBits=10',
                '--draco.quantizeTexcoordBits=12'
            ]);
            
            $process->setWorkingDirectory($tempDir);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException('Failed to convert model: ' . $process->getErrorOutput());
            }

            return new \Illuminate\Http\UploadedFile(
                $outputPath,
                'converted.glb',
                'model/gltf-binary',
                null,
                true
            );
        } finally {
            // Clean up temp directory
            Storage::deleteDirectory('temp/' . basename($tempDir));
        }
    }

    private function convertToGltf($inputPath, $outputPath, $inputFormat)
    {
        try {
            \Log::debug('Starting GLTF conversion', [
                'input_path' => $inputPath,
                'output_path' => $outputPath,
                'format' => $inputFormat
            ]);

            switch ($inputFormat) {
                case 'obj':
                    $obj2gltfPath = $this->getNpmGlobalPath() . '\\obj2gltf\\bin\\obj2gltf.js';
                    
                    if (!file_exists($obj2gltfPath)) {
                        throw new \RuntimeException('obj2gltf not found at: ' . $obj2gltfPath);
                    }

                    // Tjek om der er en MTL fil i samme mappe
                    $mtlPath = dirname($inputPath) . '/original.mtl';
                    $hasMtl = file_exists($mtlPath);

                    // Find teksturfilen hvis den eksisterer
                    $textureFiles = glob(dirname($inputPath) . '/*.*');
                    $textureFile = null;
                    foreach ($textureFiles as $file) {
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        if (in_array($ext, ['png', 'jpg', 'jpeg', 'bmp', 'tga', 'dds'])) {
                            $textureFile = basename($file);
                            break;
                        }
                    }

                    // Build command array med forbedret teksturhåndtering
                    $command = [
                        'node',
                        $obj2gltfPath,
                        '-i', basename($inputPath),
                        '-o', basename($outputPath),
                        '--checkTransparency',
                        '--separate',
                        '--embedTextures',
                        '--checkMaterials',
                        '--secure=false'
                    ];

                    // Hvis vi har både MTL og teksturfil
                    if ($hasMtl && $textureFile) {
                        $command[] = '--mtlFile=original.mtl';
                        $command[] = '--texturesPath=' . dirname($inputPath);
                        $command[] = '--packOcclusion';  // Hjælper med at bevare teksturdetaljer
                    }

                    $process = new Process($command);
                    $process->setWorkingDirectory(dirname($inputPath));
                    $process->setTimeout(600);
                    
                    // Tilføj mere detaljeret logging
                    $process->run(function ($type, $buffer) {
                        if (Process::ERR === $type) {
                            \Log::error('obj2gltf Error: ' . $buffer);
                        } else {
                            \Log::info('obj2gltf Output: ' . $buffer);
                        }
                    });

                    if (!$process->isSuccessful()) {
                        \Log::error('Conversion failed', [
                            'error' => $process->getErrorOutput(),
                            'output' => $process->getOutput(),
                            'command' => $process->getCommandLine(),
                            'working_directory' => dirname($inputPath),
                            'files_present' => scandir(dirname($inputPath))
                        ]);
                        throw new \RuntimeException('Failed to convert to GLTF: ' . $process->getErrorOutput());
                    }
                    break;

                default:
                    throw new \RuntimeException("Unsupported format: {$inputFormat}");
            }

            \Log::debug('GLTF conversion completed successfully');
        } catch (\Exception $e) {
            \Log::error('Conversion error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function isSupported($extension)
    {
        return in_array(strtolower($extension), $this->supportedFormats);
    }

    public function getSupportedFormats()
    {
        return $this->supportedFormats;
    }
} 