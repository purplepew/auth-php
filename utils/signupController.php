<?php
header('Content-Type: application/json');
require './db.php';
session_start();


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse([
        'status' => 'error',
        'message' => 'Invalid request method'
    ], 405);
}

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prevents sql injection.
    $username = $conn->real_escape_string($username);
    // Encrypt password.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Returns an error if one or more fields are empty.
    if (empty($username) || empty($email) || empty($password)) {
        jsonResponse([
            'message' => 'All fields are required.'
        ], 400);
    }

    // Returns a conflict error if username has duplicates.
    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check && $check->num_rows > 0) {
        http_response_code(409);

       jsonResponse([
            'message' => 'Username already taken.'
        ], 409);
    }

    // Insert the user info into the database.
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashedPassword', '$email')";

    // Query result.
    $result = $conn->query($sql);

    if ($result) {
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;

        jsonResponse([
            'message' => 'Successful'
        ], 200);

    } else {
        jsonResponse([
            'message' => 'Failed to create user'
        ], 400);
    }

    $conn->close();

    function jsonResponse($data = [], int $statusCode = 200): void{
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
}
?>