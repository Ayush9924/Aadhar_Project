<?php
$new_password = "Test@123"; // The password you want
$hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
echo "New Hash: " . $hashed_password;
?>
