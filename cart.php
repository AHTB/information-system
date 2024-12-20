<?php
session_start();
include 'db_connection.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "You must be logged in to view your cart.";
    exit;
}

$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="product-list.php">Products</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="register.php">Register</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h1>Your Cart</h1>
        <form method="POST" action="update_cart.php">
            <div class="cart-items">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="cart-item">
                        <img src="assets/images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                        <h2><?php echo $row['name']; ?></h2>
                        <p>$<?php echo $row['price']; ?></p>
                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                        <button type="submit">Update Quantity</button>
                    </div>
                <?php endwhile; ?>
            </div>
        </form>

        <a href="checkout.php">Proceed to Checkout</a>
    </div>

    <footer>
        <p>&copy; 2024 Clothing Store. All Rights Reserved.</p>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>
