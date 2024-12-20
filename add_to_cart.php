<?php
session_start(); // Start the session
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["message" => "You must be logged in to add items to your cart"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $userId = $_SESSION['user_id']; // Use user_id from session
    $productId = $data['product_id'] ?? null;
    $quantity = $data['quantity'] ?? 1;

    if (!$productId) {
        echo json_encode(["message" => "Product ID is required"]);
        exit;
    }

    // Check if the product is already in the cart
    $sql = "SELECT id FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the quantity if the product is already in the cart
        $sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $userId, $productId);
    } else {
        // Insert a new record if the product is not in the cart
        $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $productId, $quantity);
    }

    if ($stmt->execute()) {
        echo json_encode(["message" => "Product added to cart"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
