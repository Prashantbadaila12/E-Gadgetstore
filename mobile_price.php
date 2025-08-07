<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Mobile Price List - E-Gadgets</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .price-list {
         max-width: 1200px;
         margin: 2rem auto;
         padding: 0 2rem;
      }

      .brand-section {
         margin-bottom: 3rem;
      }

      .brand-title {
         font-size: 2rem;
         color: #333;
         margin-bottom: 1.5rem;
         border-bottom: 2px solid #eee;
         padding-bottom: 0.5rem;
      }

      .price-table {
         width: 100%;
         border-collapse: collapse;
         background: #fff;
         box-shadow: 0 1px 3px rgba(0,0,0,0.1);
         border-radius: 8px;
         overflow: hidden;
      }

      .price-table th {
         background: #f8f9fa;
         padding: 1rem;
         text-align: left;
         font-weight: 600;
         color: #333;
      }

      .price-table td {
         padding: 1rem;
         border-top: 1px solid #eee;
      }

      .model-name {
         color: #357abd;
         text-decoration: none;
         font-weight: 500;
      }

      .model-name:hover {
         text-decoration: underline;
      }

      .new-tag {
         background: #ff6b6b;
         color: white;
         padding: 0.2rem 0.5rem;
         border-radius: 3px;
         font-size: 0.8rem;
         margin-left: 0.5rem;
      }

      .brand-nav {
         display: flex;
         gap: 1rem;
         flex-wrap: wrap;
         margin-bottom: 2rem;
         background: #f8f9fa;
         padding: 1rem;
         border-radius: 8px;
      }

      .brand-nav a {
         color: #333;
         text-decoration: none;
         padding: 0.5rem 1rem;
         border-radius: 4px;
         transition: all 0.3s ease;
      }

      .brand-nav a:hover {
         background: #357abd;
         color: white;
      }

      .intro-text {
         margin-bottom: 2rem;
         color: #666;
         line-height: 1.6;
      }

      .brand-section {
         display: none;
      }
      .brand-section.active {
         display: block;
      }
      .brand-btn.active {
         background: #357abd;
         color: white;
      }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="price-list">
   <h1 class="heading">Mobile Price in Nepal</h1>
   
   <div class="intro-text">
      <p>This is the Mobile Price in Nepal section where you will find the latest price of all the smartphones available in Nepal officially (2024). With our extensive collection and competitive prices, we ensure you get the best deals on your favorite smartphones.</p>
   </div>

   <div class="brand-nav">
      <a href="#" id="samsung-btn" class="brand-btn active">Samsung</a>
      <a href="#" id="apple-btn" class="brand-btn">Apple</a>
   </div>

   <div id="samsung" class="brand-section active">
      <h2 class="brand-title">Samsung</h2>
      <table class="price-table">
         <thead>
            <tr>
               <th>Model</th>
               <th>Best Buying Price</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_samsung = $conn->prepare("SELECT * FROM `products` WHERE brand = 'samsung' ORDER BY price ASC");
            $select_samsung->execute();
            if($select_samsung->rowCount() > 0){
               while($fetch_product = $select_samsung->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
               <td>
                  <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="model-name">
                     <?= $fetch_product['name']; ?>
                     <?php if($fetch_product['is_new'] == 1): ?>
                        <span class="new-tag">New</span>
                     <?php endif; ?>
                  </a>
               </td>
               <td>Rs. <?= number_format($fetch_product['price']); ?></td>
            </tr>
            <?php
               }
            }
            ?>
         </tbody>
      </table>
   </div>

   <div id="apple" class="brand-section">
      <h2 class="brand-title">Apple</h2>
      <table class="price-table">
         <thead>
            <tr>
               <th>Model</th>
               <th>Best Buying Price</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_apple = $conn->prepare("SELECT * FROM `products` WHERE brand = 'apple' ORDER BY price ASC");
            $select_apple->execute();
            if($select_apple->rowCount() > 0){
               while($fetch_product = $select_apple->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
               <td>
                  <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="model-name">
                     <?= $fetch_product['name']; ?>
                     <?php if($fetch_product['is_new'] == 1): ?>
                        <span class="new-tag">New</span>
                     <?php endif; ?>
                  </a>
               </td>
               <td>Rs. <?= number_format($fetch_product['price']); ?></td>
            </tr>
            <?php
               }
            }
            ?>
         </tbody>
      </table>
   </div>

   <!-- Repeat similar sections for other brands -->

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const brandBtns = document.querySelectorAll('.brand-btn');
    const brandSections = document.querySelectorAll('.brand-section');

    brandBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            brandBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const targetId = this.id.replace('-btn', '');
            
            brandSections.forEach(section => {
                if (section.id === targetId) {
                    section.classList.add('active');
                } else {
                    section.classList.remove('active');
                }
            });
        });
    });
});
</script>

</body>
</html> 