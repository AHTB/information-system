<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode(["message" => "Product ID is required"]);
        exit;
    }

    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Product deleted successfully!"]);
    } else {
        echo json_encode(["message" => "Error: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
