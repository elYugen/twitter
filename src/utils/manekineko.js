import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { FontLoader } from 'three/addons/loaders/FontLoader.js';
import { TextGeometry } from 'three/addons/geometries/TextGeometry.js';

function neko() {
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const loader = new GLTFLoader();
    let model;
    camera.position.z = 5;

    const renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setAnimationLoop(animate);

    const container = document.getElementById('threejs-container');
    if (container) {
        container.appendChild(renderer.domElement);
    }

    const ambientLight = new THREE.AmbientLight(0xffffff, 1);
    scene.add(ambientLight);

    const directionalLight = new THREE.DirectionalLight(0xffffff, 0.5);
    directionalLight.position.set(1, 1, 0).normalize();
    scene.add(directionalLight);

    loader.load('/manekineko_beckoning_cat.glb', function (gltf) {
        model = gltf.scene;
        model.scale.set(1, 1, 1);
        scene.add(model);
    }, undefined, function (error) {
        console.error(error);
    });

    function animate() {
        if (model) {
            model.rotation.x += 0.01;
            model.rotation.y += 0.01;
        }
        renderer.render(scene, camera);
    }
}

export default neko;
