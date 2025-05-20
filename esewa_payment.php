<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Get order details from database
$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ? AND payment_status = 'Pending' ORDER BY id DESC LIMIT 1");
$select_orders->execute([$user_id]);
$order = $select_orders->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('location:checkout.php');
    exit();
}

$order_id = $order['order_id'];
$total_amount = $order['total_price'];

// eSewa Configuration
$esewa_url = "https://uat.esewa.com.np/epay/main"; // UAT URL
$merchant_code = "EPAYTEST"; // Replace with your merchant code in production
$success_url = "http://localhost/projectdone/esewa_success.php"; // Update with your domain
$failure_url = "http://localhost/projectdone/checkout.php"; // Update with your domain
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSewa Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'components/user_header.php'; ?>

    <section class="checkout">
        <h1 class="heading">eSewa Payment</h1>
        <div class="box-container">
            <div class="box">
                <h3>Order Summary</h3>
                <p>Order ID: <?= $order_id; ?></p>
                <p>Total Amount: Rs. <?= $total_amount; ?></p>
                
                <form action="<?= $esewa_url; ?>" method="POST">
                    <input type="hidden" name="amt" value="<?= $total_amount; ?>">
                    <input type="hidden" name="pdc" value="0">
                    <input type="hidden" name="psc" value="0">
                    <input type="hidden" name="txAmt" value="0">
                    <input type="hidden" name="tAmt" value="<?= $total_amount; ?>">
                    <input type="hidden" name="pid" value="<?= $order_id; ?>">
                    <input type="hidden" name="scd" value="<?= $merchant_code; ?>">
                    <input type="hidden" name="su" value="<?= $success_url; ?>">
                    <input type="hidden" name="fu" value="<?= $failure_url; ?>">
                    
                    <input type="submit" value="Pay with eSewa" class="btn">
                </form>
            </div>
        </div>
    </section>

    <?php include 'components/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html> 