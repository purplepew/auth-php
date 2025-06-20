<?php
// Set response to JSON
header('Content-Type: application/json');
session_start();
require './db.php';

// Reject non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['status' => 'error', 'message' => 'Invalid request method'], 405);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    jsonResponse(['message' => 'Unauthorized. Please log in first.'], 401);
}

// Get and sanitize form values
$firstName = trim($_POST['Firstname'] ?? '');
$lastName = trim($_POST['Lastname'] ?? '');
$gender = trim($_POST['Gender'] ?? '');
$birthday = trim($_POST['Birthday'] ?? '');
$address = trim($_POST['Address'] ?? '');

// Validate input
if (!$firstName || strlen($firstName) > 15 || !preg_match('/^[a-zA-Z]+$/', $firstName)) {
    jsonResponse(['message' => 'Invalid first name.'], 400);
}

if (!$lastName || !preg_match('/^[a-zA-Z]+$/', $lastName)) {
    jsonResponse(['message' => 'Invalid last name.'], 400);
}

if (!in_array(strtolower($gender), ['male', 'female', 'other'])) {
    jsonResponse(['message' => 'Gender must be Male, Female, or Other.'], 400);
}

if (!$birthday || $birthday >= date('Y-m-d')) {
    jsonResponse(['message' => 'Invalid birthday. Must be in the past.'], 400);
}

if (!$address || strlen($address) < 5) {
    jsonResponse(['message' => 'Address must be at least 5 characters.'], 400);
}

// Get logged-in user
$username = $_SESSION['username'];

// Update user's info in the database
$stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, gender = ?, birthday = ?, address = ? WHERE username = ?");
$stmt->bind_param("ssssss", $firstName, $lastName, $gender, $birthday, $address, $username);
$conn->query("UPDATE users SET has_info = 1 WHERE username = '$username'");

if ($stmt->execute()) {
    jsonResponse(['message' => 'Information saved successfully.'], 200);
} else {
    jsonResponse(['message' => 'Failed to save information.'], 500);
}

$stmt->close();
$conn->close();

// Helper: Send JSON response
function jsonResponse($data = [], int $statusCode = 200): void
{
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

?>