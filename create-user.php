<?php
// Get the user data from the request body
$userData = json_decode(file_get_contents("php://input"), true);

$conexao = new mysqli("localhost", "root", "", "casopratico");

// Check for errors
if ($conexao->connect_error) {
  die("Connection failed: " . $conexao->connect_error);
}

// Hash the user's password using bcrypt
$hashedPassword = password_hash($userData["password"], PASSWORD_BCRYPT);

// Insert the user data into the database
$sql = "INSERT INTO users (username, email, phone, password, role) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("sssss", $userData["username"], $userData["email"], $userData["phone"], $hashedPassword, $userData["role"]);
$stmt->execute();
$stmt->close();

// Return a JSON response
header("Content-Type: application/json");
echo json_encode(array("message" => "User created successfully."));
?>