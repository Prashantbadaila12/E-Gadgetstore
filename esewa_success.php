<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$status = $_GET['q'] ?? '';

// Get the latest pending order for this user
$select_order = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND payment_status = 'pending' ORDER BY placed_on DESC LIMIT 1");
$select_order->execute([$user_id]);
$order = $select_order->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    error_log("eSewa Payment Error: No pending order found for user: $user_id");
    header("Location: checkout.php");
    exit();
}

$order_id = $order['order_id'];

if ($status === 'su') {
    // Success case
    $refId = $_GET['refId'] ?? '';
    $amt = $_GET['amt'] ?? 0;

    if (empty($refId) || empty($amt)) {
        error_log("eSewa Payment Error: Missing parameters - refId: $refId, amount: $amt");
        header("Location: checkout.php");
        exit();
    }

    try {
        // Start transaction
        $conn->beginTransaction();

        // Update order status
        $update_order = $conn->prepare("UPDATE `orders` SET payment_status = 'Paid' WHERE order_id = ?");
        $update_order->execute([$order_id]);

        // Update payment record
        $update_payment = $conn->prepare("UPDATE `payments` SET status = 'Success', transaction_id = ? WHERE order_id = ? AND payment_method = 'eSewa'");
        $update_payment->execute([$refId, $order_id]);

        // Clear cart
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        // Clear session data
        unset($_SESSION['pending_order_id']);

        // Commit transaction
        $conn->commit();

        // Show success message and redirect
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Payment Success</title>
            <link rel="stylesheet" href="css/style.css">
            <style>
                .success-box {
                    max-width: 500px;
                    margin: 50px auto;
                    padding: 30px;
                    text-align: center;
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .success-icon {
                    color: #28a745;
                    font-size: 48px;
                    margin-bottom: 20px;
                }
                .success-message {
                    margin: 20px 0;
                    color: #28a745;
                    font-size: 24px;
                }
                .order-details {
                    margin: 20px 0;
                    padding: 15px;
                    background: #f8f9fa;
                    border-radius: 4px;
                    text-align: left;
                }
            </style>
        </head>
        <body>
            <?php include 'components/user_header.php'; ?>

            <div class="success-box">
                <div class="success-icon">✓</div>
                <div class="success-message">Payment Successful!</div>
                <div class="order-details">
                    <p><strong>Order ID:</strong> <?= htmlspecialchars($order_id) ?></p>
                    <p><strong>Amount Paid:</strong> Rs. <?= number_format($amt, 2) ?></p>
                    <p><strong>Transaction ID:</strong> <?= htmlspecialchars($refId) ?></p>
                </div>
                <a href="orders.php" class="btn">View Orders</a>
            </div>

            <?php include 'components/footer.php'; ?>

            <script>
                // Redirect to order success page after 3 seconds
                setTimeout(function() {
                    window.location.href = 'order_success.php?order_id=<?= $order_id ?>';
                }, 3000);
            </script>
        </body>
        </html>
        <?php
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("eSewa Success Error: " . $e->getMessage());
        header("Location: checkout.php");
        exit();
    }
} elseif ($status === 'fu') {
    // Failure case
    try {
        // Start transaction
        $conn->beginTransaction();

        // Update order status
        $update_order = $conn->prepare("UPDATE `orders` SET payment_status = 'Failed' WHERE order_id = ?");
        $update_order->execute([$order_id]);

        // Update payment record
        $update_payment = $conn->prepare("UPDATE `payments` SET status = 'Failed' WHERE order_id = ? AND payment_method = 'eSewa'");
        $update_payment->execute([$order_id]);

        // Clear session data
        unset($_SESSION['pending_order_id']);

        // Commit transaction
        $conn->commit();

        // Show error message and redirect
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Payment Failed</title>
            <link rel="stylesheet" href="css/style.css">
            <style>
                .error-box {
                    max-width: 500px;
                    margin: 50px auto;
                    padding: 30px;
                    text-align: center;
                    background: #fff;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                .error-icon {
                    color: #dc3545;
                    font-size: 48px;
                    margin-bottom: 20px;
                }
                .error-message {
                    margin: 20px 0;
                    color: #dc3545;
                    font-size: 24px;
                }
            </style>
        </head>
        <body>
            <?php include 'components/user_header.php'; ?>

            <div class="error-box">
                <div class="error-icon">✕</div>
                <div class="error-message">Payment Failed</div>
                <p>Your payment was not successful. Please try again.</p>
                <a href="checkout.php" class="btn">Return to Checkout</a>
            </div>

            <?php include 'components/footer.php'; ?>

            <script>
                // Redirect to checkout page after 3 seconds
                setTimeout(function() {
                    window.location.href = 'checkout.php';
                }, 3000);
            </script>
        </body>
        </html>
        <?php
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("eSewa Failure Error: " . $e->getMessage());
        header("Location: checkout.php");
        exit();
    }
} else {
    // Invalid status
    unset($_SESSION['pending_order_id']);
    header("Location: checkout.php");
    exit();
}
?>
