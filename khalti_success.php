<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? '';  // Get the order_id from the URL

if (empty($order_id)) {
    header('location:orders.php');  // If order_id is not provided, redirect to orders page
    exit();
}

try {
    // Fetch only the most recent order based on order_id and user_id
    $select_order = $conn->prepare("SELECT * FROM `orders` WHERE order_id = ? AND user_id = ?");
    $select_order->execute([$order_id, $user_id]);
    $order = $select_order->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        header('location:orders.php');  // Redirect if no matching order is found
        exit();
    }

    // If payment status is not already "Completed", update it to "Completed"
    if ($order['payment_status'] != 'Completed') {
        $update_status = $conn->prepare("UPDATE `orders` SET payment_status = 'Completed' WHERE order_id = ?");
        $update_status->execute([$order_id]);
    }

} catch (PDOException $e) {
    error_log("Order Success Error: " . $e->getMessage());
    header('location:orders.php');  // Redirect on error
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .order-success {
            padding: 2rem;
            text-align: center;
        }
        .order-success .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .order-details {
            margin: 2rem 0;
            padding: 1.5rem;
            background: #f8f9fa;
            border-radius: 4px;
            text-align: left;
        }
        .buttons {
            margin-top: 2rem;
        }
        .buttons .btn {
            margin: 0 1rem;
        }
    </style>

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="order-success">
    <div class="container">
        <h2>🎉 Thank You for Your Order! 🎉</h2>
        <p>Your order has been placed successfully.</p>
        
        <div class="order-details">
            <h3>Order Details</h3>
            <p><strong>Order ID:</strong> <?= htmlspecialchars($order['order_id']); ?></p>
            <p><strong>Total Products:</strong> <?= htmlspecialchars($order['total_products']); ?></p>
            <p><strong>Total Amount:</strong> Nrs.<?= number_format($order['total_price'], 2); ?>/-</p>
            <p><strong>Payment Status:</strong> <span style="color: green;"><?= htmlspecialchars($order['payment_status']); ?></span></p>
            <p><strong>Delivery Address:</strong> <?= htmlspecialchars($order['address']); ?></p>
        </div>

        <div class="buttons">
            <a href="shop.php" class="btn">Continue Shopping</a>
            <a href="orders.php" class="btn">View My Orders</a>
        </div>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
