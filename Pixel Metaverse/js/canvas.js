const canvas = document.getElementById('metaverseCanvas');
const ctx = canvas.getContext('2d');
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

let offsetX = 0, offsetY = 0;
const pixelSize = 10;
const pixels = {};

function drawPixel(x, y, color = '#FF0000') {
  const key = `${x},${y}`;
  if (!pixels[key]) {
    ctx.fillStyle = color;
    ctx.fillRect(x * pixelSize, y * pixelSize, pixelSize, pixelSize);
    pixels[key] = color;
  }
}

canvas.addEventListener('mousedown', (e) => {
  const rect = canvas.getBoundingClientRect();
  const x = Math.floor((e.clientX - offsetX) / pixelSize);
  const y = Math.floor((e.clientY - offsetY) / pixelSize);
  drawPixel(x, y);
});
let currentUser = null;

// Fetch user status
function checkAuth() {
  fetch('/php/check_auth.php')
    .then(res => res.json())
    .then(data => {
      if (data.authenticated) {
        currentUser = data.user;
        document.getElementById('authModal').style.display = 'none';
      } else {
        document.getElementById('authModal').style.display = 'block';
      }
    });
}

// Call checkAuth() on page load
checkAuth();

// Submit auth form
function submitAuth(action) {
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;

  fetch('/php/auth.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ action, username, password })
  }).then(res => res.json()).then(data => {
    if (data.status === 'success') {
      checkAuth(); // Refresh auth status
    } else {
      document.getElementById('authStatus').innerText = data.message;
    }
  });
}
// Load saved art when the page loads
fetch('/php/load_art.php')
  .then(res => res.json())
  .then(data => {
    for (const [key, color] of Object.entries(data)) {
      const [x, y] = key.split(',').map(Number);
      drawPixel(x, y, color);
    }
  });