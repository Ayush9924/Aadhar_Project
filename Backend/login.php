<?php
include "db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT id, password, role, aadhaar_verified FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["email"] = $email;
        $_SESSION["role"] = $user["role"];
        $_SESSION["aadhaar_verified"] = $user["aadhaar_verified"];

        if ($user["aadhaar_verified"] == 0) {
            header("Location: ../public/aadhar.html");
            exit();
        }

        // Redirect based on role
        if ($user["role"] === "admin") {
            header("Location: ../public/admin_dashboard.php");
        } elseif ($user["role"] === "supervisor") {
            header("Location: ../public/supervisor_dashboard.php");
        } else {
            header("Location: ../public/dashboard.php");
        }
        exit();
    } else {
        header("Location: ../public/login.html?error=invalid_credentials");
    }
}
?>
