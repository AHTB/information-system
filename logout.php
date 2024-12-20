<?php
session_start(); // Start the session
session_destroy(); // Destroy all session data
echo json_encode(["message" => "Logged out successfully"]);
?>
