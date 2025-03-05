@extends('layouts.main')

@section('title', 'Vælg Base Objekt')

@section('content')
<div class="page-container">
    <div class="form-container">
        <h1 class="page-title">Vælg Base Objekt</h1>
        
        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="model-content">
            <div class="controls">
                <select id="baseObject" name="baseObject" class="animation-dropdown">
                    <option value="">Ingen base objekt</option>
                </select>
            </div>

            <model-viewer
                id="model-viewer"
                src="{{ secure_asset('storage/' . $modelPath) }}"  
                camera-controls
                shadow-intensity="1"
                auto-rotate
                camera-orbit="45deg 55deg 2.5m"
            ></model-viewer>
        </div>

        <form method="POST" action="{{ route('complete.model.upload', ['modelId' => $modelId]) }}">
 
            @csrf
            <input type="hidden" id="selectedBaseObject" name="baseObject" value="">
            <div class="form-actions">
                <button type="submit">Færdiggør Model Upload</button>
            </div>
        </form>
    </div>
</div>

<script>
    const modelViewer = document.querySelector('#model-viewer');
    const baseObjectSelect = document.querySelector('#baseObject');
    const selectedBaseObjectInput = document.querySelector('#selectedBaseObject');

    // Funktion til at parse GLB fil
    function parseGLB(arrayBuffer) {
        const dataView = new DataView(arrayBuffer);
        
        // Læs header (12 bytes)
        const magic = dataView.getUint32(0, true);
        const version = dataView.getUint32(4, true);
        const length = dataView.getUint32(8, true);
        
        // Magic check: 0x46546C67 = "glTF"
        if (magic !== 0x46546C67) {
            throw new Error("Ugyldig .glb: magic er ikke 'glTF'");
        }
        
        let offset = 12;
        let jsonChunk = null;
        
        // Læs chunks
        while (offset < length) {
            const chunkLength = dataView.getUint32(offset, true);
            const chunkType = dataView.getUint32(offset + 4, true);
            offset += 8;
            
            const chunkData = new Uint8Array(arrayBuffer, offset, chunkLength);
            offset += chunkLength;
            
            // 0x4E4F534A (ASCII "JSON") -> JSON-chunk
            if (chunkType === 0x4E4F534A) {
                const textDecoder = new TextDecoder();
                jsonChunk = textDecoder.decode(chunkData);
            }
        }
        
        if (!jsonChunk) {
            throw new Error("Fandt ingen JSON-chunk i .glb-filen.");
        }
        
        return JSON.parse(jsonChunk);
    }

    // Funktion til at finde animationer for en specifik node
    function findAnimationsForNode(gltf, nodeName) {
        console.log('Finding animations for node:', nodeName);
        console.log('GLTF data:', gltf);
        
        if (!gltf.animations) {
            console.log('No animations found in GLTF');
            return [];
        }

        // Find først node index
        const nodeIndex = gltf.nodes.findIndex(node => node.name === nodeName);
        console.log('Node index:', nodeIndex);
        
        if (nodeIndex === -1) {
            console.log('Node not found');
            return [];
        }

        // Find animationer der refererer til denne node
        const nodeAnimations = gltf.animations.filter(animation => {
            return animation.channels.some(channel => {
                const target = channel.target;
                console.log('Checking channel target:', target);
                return target.node === nodeIndex;
            });
        });

        console.log('Found animations for node:', nodeAnimations);
        return nodeAnimations;
    }

    // Når modellen er indlæst, udfyld dropdown med nodes
    modelViewer.addEventListener('load', async () => {
        try {
            // Hent model URL
            const modelUrl = modelViewer.src;
            
            // Hent filen
            const response = await fetch(modelUrl);
            const arrayBuffer = await response.arrayBuffer();
            
            // Parse GLB
            const gltf = parseGLB(arrayBuffer);
            
            // Hent nodes fra glTF
            const nodes = gltf.nodes || [];
            console.log('Nodes found:', nodes);
            
            // Ryd eksisterende muligheder (undtagen standard)
            while (baseObjectSelect.options.length > 1) {
                baseObjectSelect.remove(1);
            }

            // Tilføj nodes som valgmuligheder
            nodes.forEach(node => {
                const option = document.createElement('option');
                option.value = node.name || 'Unnamed Node';
                option.text = node.name || 'Unnamed Node';
                baseObjectSelect.appendChild(option);
            });

            // Hvis ingen nodes blev fundet, tilføj en standard mulighed
            if (baseObjectSelect.options.length <= 1) {
                const option = document.createElement('option');
                option.value = 'default';
                option.text = 'Standard Del';
                baseObjectSelect.appendChild(option);
            }

            // Gem gltf data for senere brug
            modelViewer.gltfData = gltf;
        } catch (error) {
            console.error('Error loading model nodes:', error);
            // Tilføj en standard mulighed hvis der er fejl
            const option = document.createElement('option');
            option.value = 'default';
            option.text = 'Standard Del';
            baseObjectSelect.appendChild(option);
        }
    });

    // Når et base objekt vælges, opdater det skjulte input og afspil animationer
    baseObjectSelect.addEventListener('change', (event) => {
        const selectedNodeName = event.target.value;
        selectedBaseObjectInput.value = selectedNodeName;

        // Hvis vi har gltf data, find og afspil animationer for den valgte node
        if (modelViewer.gltfData) {
            const animations = findAnimationsForNode(modelViewer.gltfData, selectedNodeName);
            console.log('Selected node animations:', animations);
            
            if (animations.length > 0) {
                // Stop alle eksisterende animationer
                modelViewer.pause();
                modelViewer.animationName = null;
                
                // Afspil hver animation for noden
                animations.forEach((animation, index) => {
                    console.log('Playing animation:', animation.name);
                    // Vent kort mellem hver animation
                    setTimeout(() => {
                        modelViewer.animationName = animation.name;
                        modelViewer.play();
                    }, index * 1000); // 1 sekund mellem hver animation
                });
            } else {
                console.log('No animations found for selected node');
            }
        } else {
            console.log('No GLTF data available');
        }
        
        let offset = 12;
        let jsonChunk = null;
        
        // Læs chunks
        while (offset < length) {
            const chunkLength = dataView.getUint32(offset, true);
            const chunkType = dataView.getUint32(offset + 4, true);
            offset += 8;
            
            const chunkData = new Uint8Array(arrayBuffer, offset, chunkLength);
            offset += chunkLength;
            
            // 0x4E4F534A (ASCII "JSON") -> JSON-chunk
            if (chunkType === 0x4E4F534A) {
                const textDecoder = new TextDecoder();
                jsonChunk = textDecoder.decode(chunkData);
            }
        }
        
        if (!jsonChunk) {
            throw new Error("Fandt ingen JSON-chunk i .glb-filen.");
        }
        
        return JSON.parse(jsonChunk);
    }

    // Funktion til at finde animationer for en specifik node
    function findAnimationsForNode(gltf, nodeName) {
        console.log('Finding animations for node:', nodeName);
        console.log('GLTF data:', gltf);
        
        if (!gltf.animations) {
            console.log('No animations found in GLTF');
            return [];
        }

        // Find først node index
        const nodeIndex = gltf.nodes.findIndex(node => node.name === nodeName);
        console.log('Node index:', nodeIndex);
        
        if (nodeIndex === -1) {
            console.log('Node not found');
            return [];
        }

        // Find animationer der refererer til denne node
        const nodeAnimations = gltf.animations.filter(animation => {
            return animation.channels.some(channel => {
                const target = channel.target;
                console.log('Checking channel target:', target);
                return target.node === nodeIndex;
            });
        });

        console.log('Found animations for node:', nodeAnimations);
        return nodeAnimations;
    }

    // Når modellen er indlæst, udfyld dropdown med nodes
    modelViewer.addEventListener('load', async () => {
        try {
            // Hent model URL
            const modelUrl = modelViewer.src;
            
            // Hent filen
            const response = await fetch(modelUrl);
            const arrayBuffer = await response.arrayBuffer();
            
            // Parse GLB
            const gltf = parseGLB(arrayBuffer);
            
            // Hent nodes fra glTF
            const nodes = gltf.nodes || [];
            console.log('Nodes found:', nodes);
            
            // Ryd eksisterende muligheder (undtagen standard)
            while (baseObjectSelect.options.length > 1) {
                baseObjectSelect.remove(1);
            }

            // Tilføj nodes som valgmuligheder
            nodes.forEach(node => {
                const option = document.createElement('option');
                option.value = node.name || 'Unnamed Node';
                option.text = node.name || 'Unnamed Node';
                baseObjectSelect.appendChild(option);
            });

            // Hvis ingen nodes blev fundet, tilføj en standard mulighed
            if (baseObjectSelect.options.length <= 1) {
                const option = document.createElement('option');
                option.value = 'default';
                option.text = 'Standard Del';
                baseObjectSelect.appendChild(option);
            }

            // Gem gltf data for senere brug
            modelViewer.gltfData = gltf;
        } catch (error) {
            console.error('Error loading model nodes:', error);
            // Tilføj en standard mulighed hvis der er fejl
            const option = document.createElement('option');
            option.value = 'default';
            option.text = 'Standard Del';
            baseObjectSelect.appendChild(option);
        }
    });

    // Når et base objekt vælges, opdater det skjulte input og afspil animationer
    baseObjectSelect.addEventListener('change', (event) => {
        const selectedNodeName = event.target.value;
        selectedBaseObjectInput.value = selectedNodeName;

        // Hvis vi har gltf data, find og afspil animationer for den valgte node
        if (modelViewer.gltfData) {
            const animations = findAnimationsForNode(modelViewer.gltfData, selectedNodeName);
            console.log('Selected node animations:', animations);
            
            if (animations.length > 0) {
                // Stop alle eksisterende animationer
                modelViewer.pause();
                modelViewer.animationName = null;
                
                // Afspil hver animation for noden
                animations.forEach((animation, index) => {
                    console.log('Playing animation:', animation.name);
                    // Vent kort mellem hver animation
                    setTimeout(() => {
                        modelViewer.animationName = animation.name;
                        modelViewer.play();
                    }, index * 1000); // 1 sekund mellem hver animation
                });
            } else {
                console.log('No animations found for selected node');
            }
        } else {
            console.log('No GLTF data available');
        }
    });
</script>
@endsection
