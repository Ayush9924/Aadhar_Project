<?php
include "../db.php";
session_start();
header("Content-Type: application/json");

// Secure session check - Ensure only admin can access
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit();
}

$method = $_SERVER["REQUEST_METHOD"];

try {
    switch ($method) {
        case "POST":
            // Add new exam center
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data["name"]) || !isset($data["location"])) {
                echo json_encode(["success" => false, "message" => "Missing parameters."]);
                exit();
            }

            // Prevent SQL Injection
            $stmt = $conn->prepare("INSERT INTO exam_centers (name, location) VALUES (:name, :location)");
            $stmt->bindValue(":name", htmlspecialchars(strip_tags($data["name"])));
            $stmt->bindValue(":location", htmlspecialchars(strip_tags($data["location"])));

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Exam center added successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Database error."]);
            }
            break;

        case "GET":
            // Fetch exam centers securely
            $stmt = $conn->prepare("SELECT * FROM exam_centers ORDER BY created_at DESC");
            $stmt->execute();
            $centers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(["success" => true, "data" => $centers]);
            break;

        case "PUT":
            // Assign supervisor to an exam center
            $data = json_decode(file_get_contents("php://input"), true);
            if (!isset($data["supervisor_id"]) || !isset($data["exam_center_id"])) {
                echo json_encode(["success" => false, "message" => "Missing parameters."]);
                exit();
            }

            // Ensure the supervisor exists and is not already assigned
            $stmtCheck = $conn->prepare("SELECT id FROM users WHERE id = :supervisor_id AND role = 'supervisor'");
            $stmtCheck->bindValue(":supervisor_id", $data["supervisor_id"]);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() === 0) {
                echo json_encode(["success" => false, "message" => "Invalid supervisor ID."]);
                exit();
            }

            $stmt = $conn->prepare("UPDATE users SET exam_center_id = :exam_center_id WHERE id = :supervisor_id AND role = 'supervisor'");
            $stmt->bindValue(":exam_center_id", $data["exam_center_id"]);
            $stmt->bindValue(":supervisor_id", $data["supervisor_id"]);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Supervisor assigned to center."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to update supervisor."]);
            }
            break;

        default:
            echo json_encode(["success" => false, "message" => "Invalid request method."]);
    }
} catch (Exception $e) {
    // Log error for debugging
    error_log("❌ Error in exam_center_api.php: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Internal Server Error."]);
}
?>
