// Set up the scene, camera, and renderer
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / 300, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ alpha: true });
renderer.setSize(window.innerWidth, 300);
document.getElementById('threejs-container').appendChild(renderer.domElement);

// Load textures for profile picture and stats
const loader = new THREE.TextureLoader();
const profileTexture = loader.load('js/nouser.png'); // Replace with the path to your anonymous logo

// Create profile picture plane
const profileGeometry = new THREE.PlaneGeometry(2, 2);
const profileMaterial = new THREE.MeshBasicMaterial({ map: profileTexture });
const profileMesh = new THREE.Mesh(profileGeometry, profileMaterial);
profileMesh.position.set(0, 1.5, 0);
scene.add(profileMesh);

// Create stats text plane
const statsTexture = createTextTexture();
const statsGeometry = new THREE.PlaneGeometry(4, 2);
const statsMaterial = new THREE.MeshBasicMaterial({ map: statsTexture });
const statsMesh = new THREE.Mesh(statsGeometry, statsMaterial);
statsMesh.position.set(0, -1, 0);
scene.add(statsMesh);

// Position the camera
camera.position.z = 5;

// Function to create a texture from text
function createTextTexture() {
  const canvas = document.createElement('canvas');
  canvas.width = 512;
  canvas.height = 256;
  const context = canvas.getContext('2d');

  // Background
  context.fillStyle = 'rgba(255, 255, 255, 0.8)';
  context.fillRect(0, 0, canvas.width, canvas.height);

  // Random stats text
  context.fillStyle = 'black';
  context.font = '24px Arial';
  context.fillText('Random Stats:', 20, 40);
  context.fillText('Speed: ' + Math.floor(Math.random() * 100), 20, 80);
  context.fillText('Strength: ' + Math.floor(Math.random() * 100), 20, 120);
  context.fillText('Agility: ' + Math.floor(Math.random() * 100), 20, 160);
  context.fillText('Endurance: ' + Math.floor(Math.random() * 100), 20, 200);

  return new THREE.CanvasTexture(canvas);
}

// Animate the card
function animate() {
  requestAnimationFrame(animate);
  profileMesh.rotation.y += 0.01;
  statsMesh.rotation.y += 0.01;
  renderer.render(scene, camera);
}
animate();

// Handle window resize
window.addEventListener('resize', () => {
  camera.aspect = window.innerWidth / 300;
  camera.updateProjectionMatrix();
  renderer.setSize(window.innerWidth, 300);
});
