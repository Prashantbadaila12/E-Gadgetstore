<?php
session_start();
include 'components/connect.php';

$order_id = $_SESSION['pending_order_id']; // Get the order ID
if ($order_id) {
    echo "Payment was canceled. Please try again.";
    // Optionally update order status to canceled
}
?>
