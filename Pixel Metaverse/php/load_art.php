<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  exit;
}

$host = 'localhost';
$db = 'pixel_metaverse';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
$stmt = $conn->prepare("SELECT art_data FROM artworks WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

$pixels = [];
while ($row = $result->fetch_assoc()) {
  $artData = json_decode($row['art_data'], true);
  foreach ($artData as $key => $color) {
    $pixels[$key] = $color;
  }
}

echo json_encode($pixels);
?>