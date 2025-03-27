<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db.php";
session_start();
header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access. Please log in."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aadhaar_number = trim($_POST["aadhaar"]);

    // Validate Aadhaar number
    if (!preg_match("/^\d{12}$/", $aadhaar_number)) {
        echo json_encode(["success" => false, "message" => "Invalid Aadhaar number."]);
        exit();
    }

    try {
        $stmt = $conn->prepare("UPDATE users SET aadhaar_verified = 1, aadhaar_number = :aadhaar WHERE id = :user_id");
        $stmt->bindValue(":aadhaar", $aadhaar_number, PDO::PARAM_STR);
        $stmt->bindValue(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Aadhaar verified successfully! Redirecting..."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . implode(" ", $stmt->errorInfo())]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}
?>
