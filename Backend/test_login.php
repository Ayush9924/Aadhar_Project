<?php
include "db.php"; // Make sure the database connection is included

$email = "admin@example.com";

// Fetch user from database
$stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "❌ User not found in database!";
    exit();
}

// The password stored in the database
$stored_hash = $user["password"];
$input_password = "Test@123"; // The password you are using in cURL

if (password_verify($input_password, $stored_hash)) {
    echo "✅ Password matches!";
} else {
    echo "❌ Password does NOT match!";
}
?>
