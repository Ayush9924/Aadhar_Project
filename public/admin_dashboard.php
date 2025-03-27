<?php
session_start();
include "../backend/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>
        <p class="text-gray-600 mt-2">You have Admin Access.</p>
        <a href="../backend/logout.php" class="block mt-4 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
            Logout
        </a>
    </div>
</body>
</html>
