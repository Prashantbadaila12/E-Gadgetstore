<?php
include 'components/connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

try {
    // Test database connection
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    // Modified query to sort products by name for better user experience
    $select_products = $conn->prepare("SELECT id, name FROM `products` ORDER BY name ASC");
    
    if (!$select_products) {
        throw new Exception("Failed to prepare statement: " . $conn->errorInfo()[2]);
    }

    $select_products->execute();
    
    if ($select_products->errorCode() != '00000') {
        throw new Exception("Query execution failed: " . implode(" ", $select_products->errorInfo()));
    }

    $products = $select_products->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($products)) {
        echo json_encode(['message' => 'No products found', 'products' => []]);
    } else {
        echo json_encode(['products' => $products]);
    }
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
?> 