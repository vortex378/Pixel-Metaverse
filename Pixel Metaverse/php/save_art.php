<?php
session_start()
header("Content-Type: application/json");

$data = json_decode(file_get_contents('php://input'), true);
$artId = $data['artId'] ?? uniqid();
$pixelData = $data['pixels'] ?? [];

file_put_contents("../data/arts/$artId.json", json_encode($pixelData));

echo json_encode(['status' => 'saved', 'artId' => $artId]);
?>

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$pixelData = $data['pixels'] ?? [];

// Save to database
$host = 'localhost';
$db = 'pixel_metaverse';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
$stmt = $conn->prepare("INSERT INTO artworks (user_id, art_data) VALUES (?, ?)");
$stmt->bind_param("is", $user_id, json_encode($pixelData));
$stmt->execute();

echo json_encode(['status' => 'saved', 'artId' => $conn->insert_id]);
?>