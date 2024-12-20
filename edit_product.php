<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $id = $data['id'] ?? null;
    $name = $data['name'] ?? null;
    $description = $data['description'] ?? null;
    $price = $data['price'] ?? null;
    $stock = $data['stock'] ?? null;
    $image = $data['image'] ?? null;

    if (!$id || !$name || !$description || !$price || !$stock) {
        echo json_encode(["message" => "All fields are required"]);
        exit;
    }

    $sql = "UPDATE products SET name = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdssi", $name, $description, $price, $stock, $image, $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Product updated successfully!"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
