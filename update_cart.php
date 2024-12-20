<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["message" => "You must be logged in to update your cart"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $userId = $_SESSION['user_id'];
    $cartId = $data['cart_id'] ?? null;
    $quantity = $data['quantity'] ?? null;

    if (!$cartId || !$quantity) {
        echo json_encode(["message" => "Cart ID and quantity are required"]);
        exit;
    }

    $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $cartId, $userId);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Cart updated successfully"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
