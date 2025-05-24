const ws = new WebSocket("ws://localhost:8080");

function broadcastStroke(x, y, color) {
  const message = JSON.stringify({ x, y, color });
  if (ws.readyState === WebSocket.OPEN) ws.send(message);
}

// Modify drawPixel to call broadcastStroke
function drawPixel(x, y, color = '#FF0000') {
  const key = `${x},${y}`;
  if (!pixels[key]) {
    ctx.fillStyle = color;
    ctx.fillRect(x * pixelSize, y * pixelSize, pixelSize, pixelSize);
    pixels[key] = color;
    broadcastStroke(x, y, color);
  }
}

ws.onmessage = (event) => {
  const data = JSON.parse(event.data);
  drawPixel(data.x, data.y, data.color);
};