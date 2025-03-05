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

    public function convertToGlb($uploadedFile, $mtlFile = null)
    {
        $tempDir = storage_path('app/temp/' . Str::random(10));
        
        try {
            mkdir($tempDir, 0777, true);
            
            $originalExtension = strtolower($uploadedFile->getClientOriginalExtension());
            
            if ($originalExtension === 'glb') {
                return $uploadedFile;
            }

            // Save uploaded file to temp directory
            $uploadedFile->move($tempDir, 'original.' . $originalExtension);
            $originalPath = $tempDir . '/original.' . $originalExtension;
            
            // Hvis der er en MTL fil, gem den også
            if ($mtlFile) {
                $mtlFile->move($tempDir, 'original.mtl');
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

                    \Log::debug('Starting obj2gltf conversion', [
                        'obj2gltf_path' => $obj2gltfPath,
                        'input' => $inputPath,
                        'output' => $outputPath,
                        'has_mtl' => $hasMtl,
                        'mtl_path' => $mtlPath
                    ]);

                    $process = new Process([
                        'node',
                        $obj2gltfPath,
                        '-i', basename($inputPath),
                        '-o', basename($outputPath),
                        '--checkTransparency',
                        '--separate',
                        '--optimize',
                        '--cesium'
                    ]);
                    
                    $process->setWorkingDirectory(dirname($inputPath));
                    $process->setTimeout(600);
                    
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
                            'exit_code' => $process->getExitCode(),
                            'working_directory' => getcwd(),
                            'file_exists' => file_exists($inputPath),
                            'file_permissions' => fileperms($inputPath),
                            'mtl_exists' => file_exists($mtlPath)
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