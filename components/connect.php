<?php
try {
    $db_name = 'mysql:host=localhost;dbname=shop_db';
    $user_name = 'root';
    $user_password = '';

    $conn = new PDO($db_name, $user_name, $user_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $error_message = $e->getMessage();
    if (strpos($error_message, "No connection could be made") !== false) {
        echo "<script>
            alert('Database connection failed! Please make sure MySQL is running in XAMPP.');
            console.error('Database Error: " . $error_message . "');
        </script>";
    } else {
        echo "<script>
            alert('Database connection error! Please try again later.');
            console.error('Database Error: " . $error_message . "');
        </script>";
    }
    // Log the error
    error_log("Database Connection Error: " . $error_message);
    exit();
}
?>