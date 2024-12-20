<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["message" => "You must be logged in to view your cart"]);
    exit;
}

$userId = $_SESSION['user_id'];

$sql = "
    SELECT 
        c.id AS cart_id, 
        c.product_id, 
        c.quantity, 
        p.name AS product_name, 
        p.price, 
        (p.price * c.quantity) AS total_price
    FROM 
        cart c
    JOIN 
        products p ON c.product_id = p.id
    WHERE 
        c.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
}

echo json_encode($cartItems);

$stmt->close();
$conn->close();
?>
