<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

        // Insert order into `orders` table with payment status set to "pending"
        $insert_order = $conn->prepare("INSERT INTO `orders` 
            (user_id, name, number, email, method, address, total_products, total_price, placed_on) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $insert_order->execute([
            $user_id,
            $name,
            $number,
            $email,
            $method,
            $address,
            $total_products,
            $grand_total
        ]);

        // Get the inserted order's ID (auto-incremented primary key)
        $order_id = $conn->lastInsertId();  // This fetches the last inserted ID

        if ($method == "Esewa") {
            try {
                // Store the order ID for pending payment
                $_SESSION['pending_order_id'] = $order_id;

                // eSewa Payment Redirect URL (use the test environment URL)
                $esewa_url = "https://www.esewa.com.np/epay/main";

                // Success and failure callback URLs
                $success_url = "http://localhost/projectdone/esewa_success.php?q=su";
                $failure_url = "http://localhost/projectdone/esewa_success.php?q=fu";

                // eSewa transaction details
                $total_amount = $grand_total;  // Amount to be paid (in paisa)
                $tax_amount = 0;
                $service_charge = 0;
                $delivery_charge = 0;

                // Commit transaction before redirecting to eSewa
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
                            0% {
                                transform: rotate(0deg);
                            }

                            100% {
                                transform: rotate(360deg);
                            }
                        }

                        .container {
                            max-width: 500px;
                            margin: 50px auto;
                            padding: 20px;
                            background: white;
                            border-radius: 8px;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
                            <input type="hidden" name="scd" value="EPAYTEST"> <!-- eSewa test merchant code -->
                            <input type="hidden" name="su" value="<?= $success_url ?>">
                            <input type="hidden" name="fu" value="<?= $failure_url ?>">

                            <!-- Manually trigger submission -->
                            <button type="submit" style="display:none;">Proceed to eSewa</button>
                        </form>

                    </div>

                    <script>
                        window.onload = function () {
                            const form = document.getElementById('esewaForm');
                            if (form) {
                                form.submit();  // Automatically submit the form to eSewa
                            } else {
                                alert('Error: Unable to redirect to eSewa. Please try again.');
                                window.location.href = 'checkout.php';  // Redirect back if the form is not found
                            }
                        };
                    </script>
                </body>

                </html>
                <?php
                exit();  // Stop further execution after redirecting
            } catch (Exception $e) {
                // Rollback transaction on error
                if ($conn->inTransaction()) {
                    $conn->rollBack();
                }
                error_log("eSewa Payment Error: " . $e->getMessage());
                echo "<script>alert('Error processing eSewa payment: " . $e->getMessage() . "'); window.location.href='checkout.php';</script>";
                exit();
            }
        } elseif ($method == "cash on delivery") {
            // Handle COD
            try {
                // Update order status to "Pending" for COD orders
                // Fix: Assuming `id` is the correct column name for the primary key
                $update_order = $conn->prepare("UPDATE `orders` SET payment_status = 'Pending' WHERE id = ?");
                $update_order->execute([$order_id]);

                $update_order->execute([$order_id]);

                // Clear cart after placing the order
                $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
                $delete_cart->execute([$user_id]);

                // Commit transaction
                $conn->commit();

                // Redirect to success page
                header('location:order_success.php?order_id=' . $order_id);
                exit();
            } catch (PDOException $e) {
                // Catch block for COD
                $conn->rollBack();
                error_log("COD Payment Error: " . $e->getMessage());
                echo "<script>alert('Error processing COD payment: " . $e->getMessage() . "'); window.location.href='checkout.php';</script>";
                exit();
            }
        } elseif ($method == "credit card") {
            // Handle Credit Card (for simplicity, here we set it as paid)
            try {
                // Update order status to "Paid" for credit card orders
                $update_order = $conn->prepare("UPDATE `orders` SET payment_status = 'Paid' WHERE order_id = ?");
                $update_order->execute([$order_id]);

                // Clear cart after placing the order
                $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
                $delete_cart->execute([$user_id]);

                // Commit transaction
                $conn->commit();

                // Redirect to success page
                header('location:order_success.php?order_id=' . $order_id);
                exit();
            } catch (PDOException $e) {
                $conn->rollBack();
                error_log("Credit Card Payment Error: " . $e->getMessage());
                echo "<script>alert('Error processing Credit Card payment: " . $e->getMessage() . "'); window.location.href='checkout.php';</script>";
                exit();
            }
        } elseif ($method == "Khalti") {  // Khalti Payment method
    try {
        // Khalti payment integration details
        $khalti_secret_key = "6cb959c9a17e455583c784b2c3b583d6";  // Replace with your actual Khalti secret key
        $khalti_url = "https://dev.khalti.com/api/v2/epayment/initiate/";  // Khalti API URL

        // Prepare the payload for Khalti
        $khalti_payload = json_encode([
            'return_url' => "http://localhost/projectdone/khalti_success.php",  // URL to return on success
            'website_url' => "http://localhost/projectdone/khalti_success.php",  // Your website URL (Replace with your actual URL)
            'amount' => $grand_total * 100,  // Amount in paisa (100 times the total)
            'purchase_order_id' => "txn_" . $order_id,  // Unique transaction ID
            'purchase_order_name' => $total_products,  // A string or array of product names
            'customer_info' => [
                'name' => $name,
                'email' => $email,
                'phone' => $number
            ]
        ]);

        // Initialize cURL for the POST request to Khalti
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $khalti_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $khalti_payload,  // Send the payload
            CURLOPT_HTTPHEADER => [
                'Authorization: Key ' . $khalti_secret_key,  // Correct authorization header
                'Content-Type: application/json'  // Correct content-type for JSON
            ]
        ]);

        // Execute the cURL request and fetch the response
        $response = curl_exec($ch);

        // Check if cURL encountered any errors
        if (curl_errno($ch)) {
            // Log cURL errors
            error_log("Khalti cURL Error: " . curl_error($ch));
            throw new Exception("Error connecting to Khalti API.");
        }

        // Close cURL session
        curl_close($ch);

        // Log the raw response for debugging
        error_log("Khalti API Response: " . $response);

        // Decode the JSON response
        $response_data = json_decode($response, true);

        // Log the decoded response for better debugging
        error_log("Decoded Khalti Response: " . print_r($response_data, true));

        // Check if the response contains a payment URL
        if (isset($response_data['payment_url'])) {
            // Commit transaction before redirecting
            $conn->commit();

            // Clear cart after placing the order
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            // Redirect to Khalti payment gateway using the payment_url
            header("Location: " . $response_data['payment_url']);
            exit();
        } else {
            // Log and handle error if payment_url is not found
            $error_detail = isset($response_data['detail']) ? $response_data['detail'] : 'Unknown error';
            error_log("Khalti Error Response: " . json_encode($response_data));
            throw new Exception("Error initiating Khalti payment: " . $error_detail);
        }
    } catch (Exception $e) {
        // Rollback transaction if any error occurs
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }

        // Log the error message for debugging
        error_log("Khalti Payment Error: " . $e->getMessage());

        // Show error message to the user
        echo "<script>alert('Error processing Khalti payment: " . $e->getMessage() . "'); window.location.href='checkout.php';</script>";
        exit();
    }
}






    } catch (PDOException $e) {
        // Rollback transaction on error if anything fails
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
                        <option value="Khalti">Khalti</option> <!-- Added Khalti Option -->
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
