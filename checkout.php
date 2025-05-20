<?php
include 'components/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Calculate cart totals first
$grand_total = 0;
$total_products = [];

try {
    // Modified query to match shop.sql structure
    $select_cart = $conn->prepare("SELECT c.*, p.name 
                                 FROM `cart` c 
                                 LEFT JOIN `products` p ON c.pid = p.id 
                                 WHERE c.user_id = ?");
    $select_cart->execute([$user_id]);
    $cart_items = $select_cart->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cart_items as $item) {
        $total_products[] = $item['name'] . ' (' . $item['quantity'] . ')';
        $grand_total += ($item['price'] * $item['quantity']);
    }

    $total_products = implode(', ', $total_products);
} catch (PDOException $e) {
    error_log("Cart Query Error: " . $e->getMessage());
    echo "<script>alert('Error loading cart: " . $e->getMessage() . "');</script>";
    $cart_items = [];
    $total_products = '';
    $grand_total = 0;
}

if (isset($_POST['order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);

    // Get address components with default empty values
    $flat = filter_var($_POST['flat'] ?? '', FILTER_SANITIZE_STRING);
    $street = filter_var($_POST['street'] ?? '', FILTER_SANITIZE_STRING);
    $city = filter_var($_POST['city'] ?? '', FILTER_SANITIZE_STRING);
    $state = filter_var($_POST['state'] ?? '', FILTER_SANITIZE_STRING);
    $country = filter_var($_POST['country'] ?? '', FILTER_SANITIZE_STRING);
    $pin_code = filter_var($_POST['pin_code'] ?? '', FILTER_SANITIZE_STRING);

    // Combine address components
    $address = "Flat No. $flat, $street, $city, $state, $country - $pin_code";

    if (empty($cart_items)) {
        echo "<script>alert('Your cart is empty!'); window.location.href='cart.php';</script>";
        exit();
    }

    try {
        // Start transaction
        $conn->beginTransaction();
        
        // Generate order ID
        $order_id = uniqid('ORD');
        
        // Insert order - modified to match your existing orders table structure
        $insert_order = $conn->prepare("INSERT INTO `orders` 
            (order_id, user_id, name, number, email, method, address, total_products, total_price, placed_on) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        
        $insert_order->execute([
            $order_id,
            $user_id,
            $name,
            $number,
            $email,
            $method,
            $address,
            $total_products,
            $grand_total
        ]);

        if ($method == "Esewa") {
            try {
                // Store order ID in session for eSewa
                $_SESSION['pending_order_id'] = $order_id;
                
                // eSewa Payment Redirect with updated URLs and parameters
                $success_url = "http://localhost/projectdone/esewa_success.php?q=su";
                $failure_url = "http://localhost/projectdone/esewa_success.php?q=fu";
                
                // Use eSewa test environment URL
                $esewa_url = "https://rc-epay.esewa.com.np/epay/main";
                
                // Calculate amounts (amount in paisa)
                $total_amount = $grand_total;
                $tax_amount = 0;
                $service_charge = 0;
                $delivery_charge = 0;

                // Record initial payment record
                $insert_payment = $conn->prepare("INSERT INTO `payments` (order_id, user_id, amount, payment_method, transaction_id, status) VALUES (?, ?, ?, 'eSewa', ?, 'pending')");
                $insert_payment->execute([$order_id, $user_id, $total_amount, $order_id]);

                // Commit transaction before redirect
                $conn->commit();

                // Log the payment attempt
                error_log("eSewa Payment Attempt - Order ID: $order_id, Amount: $total_amount");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to eSewa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
        }
        .loader {
            margin: 20px auto;
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #60BB46;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Redirecting to eSewa</h2>
        <div class="loader"></div>
        <p>Please wait while we redirect you to eSewa payment...</p>
        
        <form action="<?= $esewa_url ?>" method="POST" id="esewaForm">
            <input type="hidden" name="amt" value="<?= $total_amount ?>">
            <input type="hidden" name="pdc" value="<?= $delivery_charge ?>">
            <input type="hidden" name="psc" value="<?= $service_charge ?>">
            <input type="hidden" name="txAmt" value="<?= $tax_amount ?>">
            <input type="hidden" name="tAmt" value="<?= $total_amount ?>">
            <input type="hidden" name="pid" value="<?= $order_id ?>">
            <input type="hidden" name="scd" value="EPAYTEST">
            <input type="hidden" name="su" value="<?= $success_url ?>">
            <input type="hidden" name="fu" value="<?= $failure_url ?>">
        </form>
    </div>

    <script>
        // Function to submit form
        function submitForm() {
            const form = document.getElementById('esewaForm');
            if (form) {
                console.log('Submitting eSewa form...');
                form.submit();
            } else {
                console.error('eSewa form not found');
                alert('Error: Unable to redirect to eSewa. Please try again.');
                window.location.href = 'checkout.php';
            }
        }

        // Try to submit form when page loads
        window.onload = function() {
            submitForm();
        };

        // Fallback: If form hasn't submitted after 3 seconds, try again
        setTimeout(function() {
            if (!document.hidden) {
                console.log('Retrying form submission...');
                submitForm();
            }
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
                error_log("eSewa Payment Error: " . $e->getMessage());
                echo "<script>alert('Error processing eSewa payment: " . $e->getMessage() . "'); window.location.href='checkout.php';</script>";
                exit();
            }
        } else {
            // For COD and Credit Card
            try {
                // Update order status to paid for COD
                $update_order = $conn->prepare("UPDATE `orders` SET payment_status = 'Paid' WHERE order_id = ?");
                $update_order->execute([$order_id]);
                
                // Record payment
                $insert_payment = $conn->prepare("INSERT INTO `payments` (order_id, user_id, amount, payment_method, transaction_id, status) VALUES (?, ?, ?, ?, ?, 'Success')");
                $insert_payment->execute([$order_id, $user_id, $grand_total, $method, $order_id]);
                
                // Clear cart
                $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
                $delete_cart->execute([$user_id]);
                
                // Commit transaction
                $conn->commit();
                
                header('location:order_success.php?order_id=' . $order_id);
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();
                error_log("COD/Credit Card Payment Error: " . $e->getMessage());
                echo "<script>alert('Error processing payment: " . $e->getMessage() . "'); window.location.href='checkout.php';</script>";
                exit();
            }
        }
    } catch (PDOException $e) {
        // Rollback transaction on error
        $conn->rollBack();
        error_log("Order Creation Error: " . $e->getMessage());
        echo "<script>alert('Error creating order: " . $e->getMessage() . "'); window.location.href='checkout.php';</script>";
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'components/user_header.php'; ?>

    <section class="checkout-orders">
        <form action="" method="POST">
            <h3>Place Your Order</h3>

            <div class="flex">
                <div class="inputBox">
                    <span>Full Name :</span>
                    <input type="text" name="name" required placeholder="Enter your name">
                </div>
                <div class="inputBox">
                    <span>Phone Number :</span>
                    <input type="number" name="number" required placeholder="Enter your number">
                </div>
                <div class="inputBox">
                    <span>Email :</span>
                    <input type="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="inputBox">
                    <span>Flat/House No :</span>
                    <input type="text" name="flat" required placeholder="e.g. flat no.">
                </div>
                <div class="inputBox">
                    <span>Street Name :</span>
                    <input type="text" name="street" required placeholder="e.g. street name">
                </div>
                <div class="inputBox">
                    <span>City :</span>
                    <input type="text" name="city" required placeholder="e.g. Kathmandu">
                </div>
                <div class="inputBox">
                    <span>State :</span>
                    <input type="text" name="state" required placeholder="e.g. Bagmati">
                </div>
                <div class="inputBox">
                    <span>Country :</span>
                    <input type="text" name="country" required placeholder="e.g. Nepal">
                </div>
                <div class="inputBox">
                    <span>Pin Code :</span>
                    <input type="number" name="pin_code" required placeholder="e.g. 44600">
                </div>
                <div class="inputBox">
                    <span>Payment Method :</span>
                    <select name="method" id="payment-method" required>
                        <option value="cash on delivery">Cash On Delivery</option>
                        <option value="credit card">Credit Card</option>
                        <option value="Esewa">eSewa</option>
                    </select>
                </div>
            </div>

            <input type="hidden" name="total_products" value="<?= htmlspecialchars($total_products); ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">

            <div class="summary">
                <h3>Order Summary</h3>
                <p>Total Products: <?= htmlspecialchars($total_products); ?></p>
                <p>Total Amount: Rs. <?= number_format($grand_total, 2); ?></p>
            </div>

            <button type="submit" name="order" class="btn <?= ($select_cart->rowCount() > 0) ? '' : 'disabled' ?>">
                Place Order
            </button>
        </form>
    </section>

    <?php include 'components/footer.php'; ?>
    <script src="js/script.js"></script>
</body>
</html>
