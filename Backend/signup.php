<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $role = $_POST["role"];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "Email already registered. Try logging in."]);
        exit();
    }

    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, aadhaar_verified) VALUES (?, ?, ?, ?, 0)");
    if ($stmt->execute([$name, $email, $password, $role])) {
        $_SESSION["user_id"] = $conn->lastInsertId(); // Store user in session
        $_SESSION["email"] = $email;
        $_SESSION["role"] = $role;
        header("Location: ../public/aadhar.html"); // Redirect to Aadhaar page
        exit();
    } else {
        echo json_encode(["success" => false, "message" => "Error occurred. Try again."]);
    }
}
?>