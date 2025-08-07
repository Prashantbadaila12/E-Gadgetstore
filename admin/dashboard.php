<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Welcome!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">Update Profile</a>
      </div>

      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            if($select_pendings->rowCount() > 0){
               while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                  $total_pendings += $fetch_pendings['total_price'];
               }
            }
         ?>
         <h3><span>Nrs.</span><?= $total_pendings; ?><span>/-</span></h3>
         <p>Total pendings</p>
         <a href="placed_orders.php" class="btn">See Orders.</a>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            if($select_completes->rowCount() > 0){
               while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                  $total_completes += $fetch_completes['total_price'];
               }
            }
         ?>
         <h3><span>Nrs.</span><?= $total_completes; ?><span>/-</span></h3>
         <p>Completed orders</p>
         <a href="placed_orders.php" class="btn">See orders</a>
      </div>

      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount()
         ?>
         <h3><?= $number_of_orders; ?></h3>
         <p>Orders Placed.</p>
         <a href="placed_orders.php" class="btn">See orders.</a>
      </div>

      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount()
         ?>
         <h3><?= $number_of_products; ?></h3>
         <p>Products added</p>
         <a href="products.php" class="btn">See products</a>
      </div>

      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount()
         ?>
         <h3><?= $number_of_users; ?></h3>
         <p>Normal users</p>
         <a href="users_accounts.php" class="btn">See Users</a>
      </div>

      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount()
         ?>
         <h3><?= $number_of_admins; ?></h3>
         <p>Admin users</p>
         <a href="admin_accounts.php" class="btn">See admins</a>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount()
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p>New messages</p>
         <a href="messages.php" class="btn">See messages</a>
      </div>

      <!-- New Box for Recent Users -->
      <div class="box">
         <h3>Recent Users</h3>
         <?php
            $select_recent_users = $conn->prepare("SELECT name, email FROM `users` ORDER BY id DESC LIMIT 5"); // Fetching last 5 registered users
            $select_recent_users->execute();
            if($select_recent_users->rowCount() > 0){
               while($fetch_recent_user = $select_recent_users->fetch(PDO::FETCH_ASSOC)){
         ?>
         <p><strong><?= $fetch_recent_user['name']; ?></strong> - <?= $fetch_recent_user['email']; ?></p>
         <?php
               }
            }else{
               echo '<p class="empty">No recent users.</p>';
            }
         ?>
         <a href="users_accounts.php" class="btn">Manage Users</a>
      </div>

      <!-- New Box for Total Product Quantity -->
      <div class="box">
         <?php
            $select_total_quantity = $conn->prepare("SELECT SUM(quantity) AS total_qty FROM `products`");
            $select_total_quantity->execute();
            $fetch_total_quantity = $select_total_quantity->fetch(PDO::FETCH_ASSOC);
            $total_products_quantity = $fetch_total_quantity['total_qty'] ?? 0; // Use 0 if SUM is null (no products)
         ?>
         <h3><?= $total_products_quantity; ?></h3>
         <p>Total stock quantity</p>
         <a href="products.php" class="btn">Manage Products</a>
      </div>

      <!-- New Box for Low Stock Products -->
      <div class="box">
         <?php
            $select_low_stock = $conn->prepare("SELECT COUNT(*) AS low_stock_count FROM `products` WHERE quantity < 10"); // Threshold set to 10
            $select_low_stock->execute();
            $fetch_low_stock = $select_low_stock->fetch(PDO::FETCH_ASSOC);
            $low_stock_count = $fetch_low_stock['low_stock_count'] ?? 0; // Use 0 if count is null
         ?>
         <h3><?= $low_stock_count; ?></h3>
         <p>Low stock products ( &lt; 10)</p>
         <a href="products.php" class="option-btn">See Products</a>
      </div>

   </div>

</section>












<script src="../js/admin_script.js"></script>
   
</body>
</html>