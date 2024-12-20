<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["message" => "You must be logged in to remove items from your cart"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $userId = $_SESSION['user_id'];
    $cartId = $data['cart_id'] ?? null;

    if (!$cartId) {
        echo json_encode(["message" => "Cart ID is required"]);
        exit;
    }

    $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $cartId, $userId);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Item removed from cart"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
