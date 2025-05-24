<?php
session_start();

// Database connection
$host = 'localhost';
$db = 'pixel_metaverse';
$user = 'root'; // Change to your DB user
$pass = '';     // Change to your DB password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle register/login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  if ($action === 'register') {
    // Register new user
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hash);
    if ($stmt->execute()) {
      echo json_encode(['status' => 'success', 'message' => 'Registered']);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Username taken']);
    }
  } elseif ($action === 'login') {
    // Login existing user
    $stmt = $conn->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows === 0) {
      echo json_encode(['status' => 'error', 'message' => 'User not found']);
      exit;
    }

    $stmt->bind_result($id, $hash);
    $stmt->fetch();

    if (password_verify($password, $hash)) {
      $_SESSION['user_id'] = $id;
      $_SESSION['username'] = $username;
      echo json_encode(['status' => 'success', 'user' => ['id' => $id, 'username' => $username]]);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Wrong password']);
    }
  }
}
?>