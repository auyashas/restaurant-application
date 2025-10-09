<?php
require_once "config.php";

// --- SET YOUR ADMIN CREDENTIALS HERE ---
$username = "admin";
$password = "Admin123"; // Choose a strong password
$role = "super_admin";
// ------------------------------------

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare an insert statement
$sql = "INSERT INTO admins (username, password, role) VALUES (?, ?, ?)";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    if ($stmt->execute()) {
        echo "Admin user created successfully! You can now delete this file.";
    } else {
        echo "Error: Could not execute the query: " . $mysqli->error;
    }
    $stmt->close();
} else {
    echo "Error: Could not prepare the query: " . $mysqli->error;
}

$mysqli->close();
?>