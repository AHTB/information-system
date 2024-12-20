<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["message" => "You must be logged in to proceed with checkout"]);
    exit;
}

$userId = $_SESSION['user_id'];

// Get the items in the user's cart
$sql = "SELECT c.id, c.product_id, c.quantity, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["message" => "Your cart is empty"]);
    exit;
}

// Calculate the total price
$totalAmount = 0;
$orderDetails = [];
while ($row = $result->fetch_assoc()) {
    $totalAmount += $row['quantity'] * $row['price'];
    $orderDetails[] = $row;
}

// Create the order in the orders table
$sql = "INSERT INTO orders (user_id, total_amount, order_date) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("id", $userId, $totalAmount);
$stmt->execute();
$orderId = $stmt->insert_id;  // Get the last inserted order ID

// Insert the cart items into the order_items table
foreach ($orderDetails as $item) {
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $orderId, $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();

    // Deduct the stock from the product (if applicable)
    $sql = "UPDATE products SET stock = stock - ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
    $stmt->execute();
}

// Clear the cart after successful checkout
$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();

echo json_encode(["message" => "Checkout successful! Your order ID is: " . $orderId]);

$stmt->close();
$conn->close();
?>
