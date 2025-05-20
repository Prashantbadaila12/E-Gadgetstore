<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Display error message if payment failed
$error_message = "Payment Failed! Please try again.";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="payment-failed">
    <h3>Payment Unsuccessful</h3>
    <p><?= $error_message ?></p>
    <p>If you believe this is an error, please contact our support team or try again later.</p>
    <a href="cart.php" class="btn">Go Back to Cart</a>
</section>

</body>
</html>
