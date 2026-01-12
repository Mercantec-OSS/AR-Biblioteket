@extends('layouts.main')

@section('content')
<style>
html {
    scroll-behavior: smooth;
}

/* Typography */
.container h1, .container h2, .container h5 {
    font-family: 'Segoe UI', Roboto, sans-serif;
    color: #002244; /* darker, richer blue */
}

.container p, .container li {
    font-size: 1rem;
    line-height: 1.6;
    color: #333;
}

.container strong {
    color: #0055aa;
}

.container em {
    font-style: italic;
    color: #0077cc;
}

/* Table of Contents (Indholdsfortegnelse) */
.list-group {
    background-color: #f0f4f8; /* subtle light gray-blue */
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.list-group-item {
    border: none;
    padding: 0.6rem 1rem;
    font-size: 1rem;
    margin-bottom: 0.25rem;
    border-radius: 6px;
    transition: all 0.2s ease;
    background-color: #cce0ff; /* richer light blue */
}

.list-group-item a {
    color: #003366; /* darker blue text */
    font-weight: 500;
    text-decoration: none;
}

.list-group-item a:hover {
    text-decoration: underline;
    color: #001a33;
    background-color: #b3d1ff;
}

/* Sections */
section {
    padding: 1.5rem 1rem;
    border-left: 4px solid #1E90FF; /* modern accent line */
    margin-bottom: 2rem;
    background: linear-gradient(90deg, #ffffff 0%, #f9fbff 100%);
    border-radius: 8px;
    transition: transform 0.2s ease;
}

section:hover {
    transform: translateY(-2px);
}

/* HR separator */
hr {
    border: 0;
    border-top: 1px solid #ccc;
    margin: 2rem 0;
}

/* Code blocks */
pre {
    background-color: #1E1E2F;
    color: #dcdcdc;
    padding: 1rem;
    border-radius: 8px;
    overflow-x: auto;
    font-family: 'Fira Code', monospace;
    font-size: 0.9rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

pre code {
    background: none;
    color: inherit;
}

/* 3D Guide Button */
.stylish-btn {
    font-size: 1rem;
    font-weight: 600;
    padding: 0.6rem 1.6rem;
    border: none;
    border-radius: 50px;
    background-color: #002244; /* darker 3D blue */
    color: white;
    cursor: pointer;
    text-transform: uppercase;
    box-shadow: 0 6px 14px rgba(0,34,68,0.6);
    transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.stylish-btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,34,68,0.7);
    background-color: #001a33;
}

.stylish-btn:active {
    transform: translateY(0) scale(0.97);
    box-shadow: 0 4px 10px rgba(0,34,68,0.5);
}

/* Optional: subtle hover effect for section headings */
section h2:hover {
    color: #1E90FF;
}
</style>

<div class="container my-5">
    <h1 class="mb-4"><strong>3D-model tjekliste – Klargør til <em>Byg og lær</em></strong></h1>
    <h2 class="mb-4">Indholdsfortegnelse</h2>
<ul class="list-group mb-4">
    <li class="list-group-item"><a href="#sec1">1. Skaler først</a></li>
    <li class="list-group-item"><a href="#sec2">2. Flyt origin til midten af hver del</a></li>
    <li class="list-group-item"><a href="#sec3">3. Ryd op i antallet af objekter</a></li>
    <li class="list-group-item"><a href="#sec4">4. Læg alt i samlingen “Collection”</a></li>
    <li class="list-group-item"><a href="#sec5">5. Navngiv delene pænt</a></li>
    <li class="list-group-item"><a href="#sec6">6. Clone-tricket</a></li>
    <li class="list-group-item"><a href="#sec7">7. Check normals</a></li>
    <li class="list-group-item"><a href="#sec8">8. Tjek for dobbelte vertices og ikke-manifold geometri</a></li>
    <li class="list-group-item"><a href="#sec9">9. Eksporter til .glb / glTF</a></li>
    <li class="list-group-item"><a href="#sec10">10. Prepare for AR – Blender Script (v2)</a></li>
</ul>


    <p><strong>Blender som fælles værktøj</strong></p>
    <p>
        Selv om du modellerer i et andet CAD-program, er <strong>Blender</strong> (gratis, Windows / macOS / Linux) genial som sidste stop:
        det åbner de fleste formater – <strong>STEP, FBX, OBJ, STL, IGES, glTF</strong>, m.fl. – og giver den nemmeste vej til animation,
        eksport til <strong>.glb</strong> og de små justeringer, der gør en model VR-klar.
    </p>
    <p>
        Hvert trin nedenfor består af:
    </p>
    <ol>
        <li><strong>Hvorfor?</strong> – hvad der går galt i VR/AR, hvis du springer det over</li>
        <li><strong>Sådan gør du</strong> – detaljeret vejledning i <strong>Blender, Fusion 360, SolidWorks</strong> og <strong>Onshape</strong> (spring bare til det program, du bruger).</li>
    </ol>

    <hr class="my-4">

    <!-- Section 1 -->
    <section id="sec1" class="mb-5">
        <h2>1. Skaler først</h2>

        <h5><strong>Hvorfor?</strong></h5>
        <p>
            VR-rummet i <strong>Byg og lær</strong> er <strong>2,5 × 2,5 m</strong>. Alt, der stikker udenfor, er besværligt at se og gribe.
            Hvis den virkelige maskine er større, giver en nedskaleret læringsmodel ofte bedre mening.
        </p>

        <h5><strong>Sådan gør du</strong></h5>

        <p><strong>Blender</strong></p>
        <ul>
            <li>Åbn <strong>Scene Properties ▸ Units</strong> → vælg <strong>Metric</strong>.</li>
            <li>Markér alt (<kbd>A</kbd>) → tryk <kbd>N</kbd> → skriv de ønskede dimensioner (f.eks. X = 2 m).</li>
            <li>Tryk <kbd>Ctrl</kbd> + <kbd>A</kbd> ▸ <strong>Apply</strong> ▸ <strong>Scale</strong> for at låse skalaen.</li>
        </ul>

        <p><strong>Fusion 360</strong></p>
        <ul>
            <li>I <strong>Document Settings</strong> klik <strong>Units</strong> → vælg <strong>mm</strong> eller <strong>cm</strong>.</li>
            <li>Vælg alle komponenter → <strong>Modify ▸ Scale</strong> → brug “<strong>Absolute</strong>” og tast målet.</li>
            <li>Gem eller eksporter – ændringen er permanent.</li>
        </ul>

        <p><strong>SolidWorks</strong></p>
        <ul>
            <li>Gå til <strong>Options ▸ Document Properties ▸ Units</strong> → <strong>metric</strong>.</li>
            <li>Markér hele feature-træet → <strong>Insert ▸ Features ▸ Scale</strong> → “<strong>About centroid</strong>” → skriv faktor.</li>
        </ul>

        <p><strong>Onshape</strong></p>
        <ul>
            <li>Klik <strong>Workspace Units</strong> i bunden → vælg <strong>mm</strong>.</li>
            <li>Vælg alle parts → <strong>Transform ▸ Scale</strong> → skriv faktor eller længde – bekræft med ✓.</li>
        </ul>
    </section>

    <hr class="my-4">
    <!-- Section 2 -->
    <section id="sec2" class="mb-5">
        <h2>2. Flyt origin til midten af hver del</h2>
        <p><em>(Vi bruger ordet "del" frem for body/part for klarhed.)</em></p>

        <h5><strong>Hvorfor?</strong></h5>
        <p>
            <strong>Origin</strong> = gribe- og rotationspunktet i VR. Ligger den i et hjørne, føles det som at løfte en hammer i skaftet – meget svært at styre.
        </p>

        <h5><strong>Sådan gør du</strong></h5>

        <p><strong>Blender</strong></p>
        <ul>
            <li>Markér en del → <kbd>Shift</kbd> + <kbd>S</kbd> ▸ <strong>Cursor to Selected</strong>.</li>
            <li>Vælg <strong>Object ▸ Set Origin ▸ Origin to 3D Cursor</strong>.</li>
        </ul>

        <p><strong>Fusion 360</strong></p>
        <ul>
            <li>Åbn <strong>Move/Copy</strong> → vælg <strong>"Point to Point"</strong>.</li>
            <li>Klik midten af delen → Klik i nulpunktet → <strong>OK</strong> (Fusion gemmer et nyt referencepunkt).</li>
        </ul>

        <p><strong>SolidWorks</strong></p>
        <ul>
            <li>Indsæt <strong>Reference Geometry ▸ Coordinate System</strong> → vælg <strong>Center of Mass</strong>.</li>
            <li>Vælg dette koordinatsystem som origin, når du eksporterer.</li>
        </ul>

        <p><strong>Onshape</strong></p>
        <ul>
            <li>Åben delen i <strong>Assembly</strong>.</li>
            <li>Højreklik delen → <strong>Add Mate Connector</strong>.</li>
            <li>Vælg to midtpunkter → gem – connectoren bruges som eksport-origin.</li>
        </ul>
    </section>

    <hr class="my-4">

    <!-- Section 3 -->
    <section id="sec3" class="mb-5">
        <h2>3. Ryd op i antallet af objekter</h2>

        <h5><strong>Hvorfor?</strong></h5>
        <p>
            Eleverne har ca. 10 minutter til at samle modellen. Hvis din del har 60 dele ⇒ 10 sekunder pr. del – 
            det er nogenlunde smertegrænsen. Flere dele = stress og forvirring, hvilket kan nedsætte læringsudbyttet.
        </p>

        <h5><strong>Sådan gør du</strong></h5>

        <p><strong>Blender</strong></p>
        <ul>
            <li>Fjern småting (<kbd>X</kbd> ▸ <strong>Delete</strong>).</li>
            <li>Markér dele der hører sammen → <kbd>Ctrl</kbd> + <kbd>J</kbd> ▸ <strong>Join</strong>.</li>
        </ul>

        <p><strong>Fusion 360</strong></p>
        <ul>
            <li>Brug <strong>Combine (Join)</strong> for at smelte flere bodies sammen.</li>
            <li>Skjul/skrab ubetydelige features med <strong>Remove</strong> eller <strong>Simplify</strong>.</li>
        </ul>

        <p><strong>SolidWorks</strong></p>
        <ul>
            <li>Markér features i <strong>FeatureTree</strong> → <strong>Combine</strong> eller <strong>Delete/Keep Body</strong>.</li>
            <li>Brug <strong>Defeature</strong>-værktøjet til hurtigt at fjerne små fillets/skruer.</li>
        </ul>

        <p><strong>Onshape</strong></p>
        <ul>
            <li>Markér flere <strong>Parts</strong> i <strong>Part Studio</strong> → <strong>Boolean ▸ Union</strong>.</li>
            <li>Skjul bolte/skruer med <strong>Suppress</strong> i Feature-listen.</li>
        </ul>
    </section>

    <hr class="my-4">

    <!-- Section 4 -->
    <section id="sec4" class="mb-5">
        <h2>4. Læg alt i samlingen “Collection”</h2>

        <h5><strong>Hvorfor?</strong></h5>
        <p>
            <strong>AR-biblioteket</strong> kigger kun i standard-samlingen – alt andet ignoreres.
        </p>

        <h5><strong>Sådan gør du</strong></h5>

        <p><strong>Blender</strong></p>
        <ul>
            <li>Markér alle mesh-dele → tryk <kbd>M</kbd> → <strong>Move to Collection</strong> → vælg <strong>Collection</strong>.</li>
            <li>Eller træk dem manuelt i <strong>Outliner</strong>.</li>
        </ul>

        <p><strong>Fusion 360 / SolidWorks / Onshape</strong></p>
        <ul>
            <li>Ikke relevant – men sørg for at alt du vil eksportere ligger i én fil/enhed (fx én <strong>Assembly</strong> eller <strong>Part Studio</strong>), så eksport-<strong>glTF</strong>’en ikke mangler noget.</li>
        </ul>
    </section>

    <hr class="my-4">

    <!-- Section 5 -->
    <section id="sec5" class="mb-5">
        <h2>5. Navngiv delene pænt</h2>

        <h5><strong>Hvorfor?</strong></h5>
        <p>
            <strong>Byg og lær</strong> er et samarbejdende læringsrum. Eleverne inde i VR skal bede
            klassekammerater udenfor om hjælp:
        </p>
        <p>
            “Hvor skal <strong>Gear A</strong> placeres!” er klart og tydeligt – “Hvor skal <strong>Cube.003</strong> placeres” er ikke 
            ret brugbart. Korrekte navne træner fagtermer.
        </p>
        <p><strong>OBS:</strong> Se også punkt 6.</p>

        <h5><strong>Sådan gør du</strong></h5>

        <p><strong>Blender</strong></p>
        <ul>
            <li>Dobbeltklik navnet i <strong>Outliner</strong> → skriv fx <strong>Motorhus</strong>.</li>
        </ul>

        <p><strong>Fusion 360</strong></p>
        <ul>
            <li>Langsomt dobbeltklik på body/komponentnavnet → omdøb.</li>
        </ul>

        <p><strong>SolidWorks</strong></p>
        <ul>
            <li>Højreklik på <strong>Body</strong> → <strong>Rename</strong>.</li>
        </ul>

        <p><strong>Onshape</strong></p>
        <ul>
            <li>Dobbeltklik på <strong>Part</strong>-navnet i Feature-panelet.</li>
        </ul>
    </section>

    <hr class="my-4">

    <!-- Section 6 -->
    <section id="sec6" class="mb-5">
        <h2>6. Clone-tricket</h2>

        <h5><strong>Hvorfor?</strong></h5>
        <p>
            I VR sidder hver del på en tavleplads. Hvis du har ti identiske bolte vil tavlen ellers fylde for 
            meget. Med suffix-navne kan eleverne tage flere dele fra samme plads; tavlepladsen forsvinder 
            først når alle kopier er brugt.
        </p>

        <h5><strong>Sådan gør du</strong></h5>
        <ul>
            <li>Omdøb kopierne <strong>Bolt-1</strong>, <strong>Bolt-2</strong>, … (samme basenavn + <code>-nummer</code>).</li>
            <li><strong>AR-systemet</strong> forstår nummer-suffikset og lader brugeren plukke dem én efter én fra samme punkt.</li>
            <li>(Fungerer ens i alle fire programmer – det handler blot om navngivningen).</li>
        </ul>
    </section>
    <hr class="my-4">

     <!-- Section 7 -->
    <section id="sec7" class="mb-5">
        <h2>7. Check normals</h2>

         <h5><strong>Hvorfor?</strong></h5>
         <p>
            Normals bestemmer, hvilken side af fladen der er “foran”. Forkerte normals gør, at modeller kan se gennemsigtige ud eller lyse forkert i VR/AR.
         </p>

        <h5><strong>Sådan gør du</strong></h5>

         <p><strong>Blender</strong></p>
        <ul>
           <li>Vælg modellen → <kbd>Tab</kbd> for Edit Mode → <strong>Overlay ▸ Face Orientation</strong> for at se blå (rigtig) vs rød (forkert).</li>
         <li>Vælg røde flader → <strong>Mesh ▸ Normals ▸ Flip</strong> eller <strong>Recalculate Outside</strong>.</li>
        </ul>

         <p><strong>Fusion 360 / SolidWorks / Onshape</strong></p>
        <ul>
            <li>Disse CAD-programmer holder normalt styr på normals automatisk – men ved import/eksport til Blender skal du tjekke.</li>
        </ul>
    </section>

    <hr class="my-4">

<!-- Section 8 -->
<section id="sec8" class="mb-5">
    <h2>8. Tjek for dobbelte vertices og ikke-manifold geometri</h2>

    <h5><strong>Hvorfor?</strong></h5>
    <p>
        Ikke-manifold geometri kan bryde VR/AR-importen og gøre det umuligt at sætte materialer korrekt. Dobbelte vertices kan give mærkelige renderingseffekter.
    </p>

    <h5><strong>Sådan gør du</strong></h5>

    <p><strong>Blender</strong></p>
    <ul>
        <li>Vælg modellen → <kbd>Tab</kbd> → <strong>Mesh ▸ Clean Up ▸ Merge by Distance</strong> for dobbelte vertices.</li>
        <li>For ikke-manifold: <strong>Select ▸ Select All by Trait ▸ Non-Manifold</strong> → ret fejlen.</li>
    </ul>

    <p><strong>Fusion 360 / SolidWorks / Onshape</strong></p>
    <ul>
        <li>Brug <strong>Check</strong> eller <strong>Validate</strong> værktøjer, der identificerer geometrifejl før eksport.</li>
    </ul>
</section>

<hr class="my-4">

<!-- Section 9 -->
<section id="sec9" class="mb-5">
    <h2>9. Eksporter til .glb / glTF</h2>

    <h5><strong>Hvorfor?</strong></h5>
    <p>
        .glb/.gltf er standardformatet for VR/AR i <strong>Byg og lær</strong>. Andre formater kræver konvertering og kan miste materialer eller skala.
    </p>

    <h5><strong>Sådan gør du</strong></h5>

    <p><strong>Blender</strong></p>
    <ul>
        <li>Fil ▸ Eksporter ▸ <strong>glTF 2.0 (.glb/.gltf)</strong>.</li>
        <li>Vælg <strong>Format: Binary (.glb)</strong> for en enkelt fil.</li>
        <li>Check <strong>Include ▸ Selected Objects</strong> hvis kun visse dele skal med.</li>
    </ul>

    <p><strong>Fusion 360 / SolidWorks / Onshape</strong></p>
    <ul>
        <li>Eksporter til <strong>STEP / OBJ / FBX</strong> → importer i Blender → gem som .glb.</li>
    </ul>
</section>

<hr class="my-4">

<!-- Section 10 -->
<section id="sec10" class="mb-5">
    <h2>10. Prepare for AR – Blender Script (v2)</h2>

    <h5><strong>Bemærk</strong></h5>
    <p>
        Gem din <strong>.blend</strong> først – scriptet ændrer scenen! Kopier denne kode:
    </p>

    <pre><code>
==============================================================
Prepare for AR – v2 (ingen rod-Empty, ingen auto-decimate,
altid explode-animation, alt i "Collection")
==============================================================
import bpy
from mathutils import Vector

───── Indstillinger du evt. vil justere ───────────────────────
FPS = 24
TOTAL_FRAMES = 120
STRENGTH = 0.30
SCALE_FACTOR = 0.10
---------------------------------------------------------------

def set_units_metric():
    scn = bpy.context.scene
    scn.unit_settings.system = 'METRIC'
    scn.unit_settings.scale_length = 1.0

def move_to_collection(objs, coll_name="Collection"):
    coll = bpy.data.collections.get(coll_name)
    if coll is None:
        coll = bpy.data.collections.new(coll_name)
        bpy.context.scene.collection.children.link(coll)
    for o in objs:
        for c in o.users_collection:
            c.objects.unlink(o)
        coll.objects.link(o)

def origin_to_center(obj):
    bpy.context.view_layer.objects.active = obj
    obj.select_set(True)
    bpy.ops.object.origin_set(type='ORIGIN_CENTER_OF_VOLUME', center='MEDIAN')
    obj.select_set(False)

def apply_rot_scale(obj):
    obj.select_set(True)
    bpy.context.view_layer.objects.active = obj
    bpy.ops.object.transform_apply(rotation=True, scale=True)
    obj.select_set(False)

def pack_resources():
    bpy.ops.file.pack_all()

def create_explode_animation(objs, fps, total_frames, strength, scale_factor):
    scene = bpy.context.scene
    scene.render.fps = fps
    scene.frame_end = total_frames
    explode_at = total_frames // 2
    start_pos = {o: o.location.copy() for o in objs}

    for o in objs:
        o.keyframe_insert("location", frame=1)

    for o in objs:
        if o.animation_data and o.animation_data.action:
            o.animation_data.action.name = o.name

    for o in objs:
        dir_vec = o.location if o.location.length else Vector((1,0,0))
        dir_vec.normalize()
        o.location += dir_vec * (strength + o.location.length * scale_factor)
        o.keyframe_insert("location", frame=explode_at)

    for o in objs:
        o.location = start_pos[o]
        o.keyframe_insert("location", frame=total_frames)

def prepare_for_ar():
    set_units_metric()
    mesh_objs = [o for o in bpy.data.objects if o.type == 'MESH']
    move_to_collection(mesh_objs, "Collection")
    for o in mesh_objs:
        origin_to_center(o)
        apply_rot_scale(o)
    pack_resources()
    create_explode_animation(mesh_objs, FPS, TOTAL_FRAMES, STRENGTH, SCALE_FACTOR)
    print(" AR-klargøring færdig! Eksportér nu som glTF (.glb)")

————— Kør funktionen ——————————
prepare_for_ar()
    </code></pre>
</section>
@endsection