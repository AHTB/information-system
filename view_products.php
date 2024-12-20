<?php
include 'db_connection.php';

$sql = "SELECT id, name, description, price, stock, image FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $products = [];
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo json_encode(["message" => "No products found"]);
}

$conn->close();
?>
