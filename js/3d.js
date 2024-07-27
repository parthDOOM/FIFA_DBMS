// Set up the scene, camera, and renderer
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);

const container = document.getElementById('threejs-container');
const renderer = new THREE.WebGLRenderer({ alpha: true }); // Enable transparency
renderer.setSize(container.clientWidth, container.clientHeight);
container.appendChild(renderer.domElement);

// Load the images as textures
const loader = new THREE.TextureLoader();
const mapTexture = loader.load('js/footballmap.jpg'); // Replace with the correct path
const texture = loader.load('js/footballtexture.jpg'); // Replace with the correct path

// Create a sphere geometry and apply the texture
const geometry = new THREE.SphereGeometry(5, 64, 64); // Increase segment count for better quality
const material = new THREE.MeshStandardMaterial({
    map: texture,
    normalMap: mapTexture
});
const sphere = new THREE.Mesh(geometry, material);
scene.add(sphere);

// Add lighting
const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
scene.add(ambientLight);

const pointLight = new THREE.PointLight(0xffffff, 1);
pointLight.position.set(10, 10, 10);
scene.add(pointLight);

// Position the camera
camera.position.z = 15;

// Render the scene
function animate() {
    requestAnimationFrame(animate);
    sphere.rotation.y += 0.01; // Rotate the football for a better view
    renderer.render(scene, camera);
}
animate();

// Handle window resize
window.addEventListener('resize', () => {
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
});
