<?php
session_start(); // Start the session

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["message" => "Unauthorized: Admins only"]);
    exit; // Stop execution of the script
}

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $name = $data['name'] ?? null;
    $description = $data['description'] ?? null;
    $price = $data['price'] ?? null;
    $stock = $data['stock'] ?? null;
    $image = $data['image'] ?? null;

    if (!$name || !$description || !$price || !$stock) {
        echo json_encode(["message" => "All fields are required"]);
        exit;
    }

    $sql = "INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $name, $description, $price, $stock, $image);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Product added successfully!"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
