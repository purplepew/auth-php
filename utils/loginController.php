<?php
// Set response content type to JSON
header('Content-Type: application/json');

// Start session to store user info after login
session_start();

// Include database connection
require './db.php';

// Return 405 if not a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse([
        'status' => 'error',
        'message' => 'Invalid request method'
    ], 405);
}

// Get values from the POST request
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// Escape the username to prevent SQL injection
$username = $conn->real_escape_string($username);

// Return error if username or password is missing
if (!$username || !$password) {
    jsonResponse([
        'status' => 'error',
        'message' => 'Username and password are required'
    ], 400);
}

// Query the user by username
$sql = "SELECT * FROM users WHERE username = '$username'";
$foundUser = $conn->query($sql);

// Check if user exists
if ($foundUser && $foundUser->num_rows === 1) {
    $user = $foundUser->fetch_assoc();

    // Verify hashed password
    if (password_verify($password, $user['password'])) {
        // Save user info to session
        $_SESSION['username'] = $username;

        jsonResponse([
            'message' => 'Login successful'
        ], 200);
    } else {
        // Wrong password
        jsonResponse([
            'message' => 'Wrong password'
        ], 400);
    }
}else{ 
    // Username not found
    jsonResponse([
        'message' => 'Username not found'
    ], 404);
}

// If user not found, close the connection
$conn->close();

// JSON Response helper
function jsonResponse($data = [], int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>
