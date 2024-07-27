// // Set up the scene, camera, and renderer
// const scene = new THREE.Scene();
// const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);

// const container = document.getElementById('threejs-container');
// const renderer = new THREE.WebGLRenderer({ alpha: true }); // Enable transparency
// renderer.setSize(container.clientWidth, container.clientHeight);
// container.appendChild(renderer.domElement);

// // Load the images as textures
// const loader = new THREE.TextureLoader();
// const mapTexture = loader.load('js/footballmap.jpg'); // Replace with the correct path
// const texture = loader.load('js/footballtexture.jpg'); // Replace with the correct path

// // Create a sphere geometry and apply the texture
// const geometry = new THREE.SphereGeometry(5, 64, 64); // Increase segment count for better quality
// const material = new THREE.MeshStandardMaterial({
//     map: texture,
//     normalMap: mapTexture
// });
// const sphere = new THREE.Mesh(geometry, material);
// scene.add(sphere);

// // Add lighting
// const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
// scene.add(ambientLight);

// const pointLight = new THREE.PointLight(0xffffff, 1);
// pointLight.position.set(10, 10, 10);
// scene.add(pointLight);

// // Position the camera
// camera.position.z = 15;

// // Render the scene
// function animate() {
//     requestAnimationFrame(animate);
//     sphere.rotation.y += 0.01; // Rotate the football for a better view
//     renderer.render(scene, camera);
// }
// animate();

// // Handle window resize
// window.addEventListener('resize', () => {
//     camera.aspect = container.clientWidth / container.clientHeight;
//     camera.updateProjectionMatrix();
//     renderer.setSize(container.clientWidth, container.clientHeight);
// });


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

// Variables for bouncing animation
let bounceSpeed = 0.485; // Increase the initial bounce speed
let gravity = 0.0215; // Decrease the gravity effect slightly

let velocity = bounceSpeed; // Initial velocity
let isBouncingUp = false;
let bounceHeight = 0; // Initial height

// Render the scene
function animate() {
    requestAnimationFrame(animate);

    // Rotate the football
    sphere.rotation.y += 0.01;

    // Update velocity and position for bouncing effect
    if (isBouncingUp) {
        velocity -= gravity;
        sphere.position.y += velocity;
        if (velocity <= 0) {
            isBouncingUp = false;
        }
    } else {
        velocity += gravity;
        sphere.position.y -= velocity;
        if (sphere.position.y <= bounceHeight) {
            sphere.position.y = bounceHeight;
            velocity = bounceSpeed;
            isBouncingUp = true;
        }
    }

    renderer.render(scene, camera);
}
animate();

// Handle window resize
window.addEventListener('resize', () => {
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
});
