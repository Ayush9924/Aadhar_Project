<?php
$host = "localhost";  
$dbname = "exam_auth_system";  
$username = "root";  // Change this if necessary  
$password = "";      // Set the MySQL password if required  

try {
    // Establish PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set PDO to fetch associative arrays by default
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handle database connection errors
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $e->getMessage()]));
}
?>
