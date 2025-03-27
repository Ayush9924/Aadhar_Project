<?php
$stored_hash = '$2y$10$KIX/3eMdfq1/N5R08H7J9e3BBOhFwosbE9MDCr5HgJtKo4h9IAr.u'; // Hash from database
$input_password = 'Test@123'; // The password you are using in cURL

if (password_verify($input_password, $stored_hash)) {
    echo "✅ Password matches!";
} else {
    echo "❌ Password does NOT match!";
}
?>

