<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>E-Gadgets</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .special-offer {
         background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
         padding: 2rem;
         text-align: center;
         margin: 2rem 0;
         border-radius: 10px;
         box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      }
      
      .countdown {
         display: flex;
         justify-content: center;
         gap: 2rem;
         margin: 1rem 0;
      }
      
      .countdown div {
         background: #fff;
         padding: 1rem;
         border-radius: 5px;
         min-width: 80px;
         box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      }
      
      .testimonials {
         padding: 3rem 0;
         background: #f9f9f9;
      }
      
      .testimonial-card {
         background: #fff;
         padding: 2rem;
         border-radius: 10px;
         box-shadow: 0 5px 15px rgba(0,0,0,0.1);
         margin: 1rem;
         text-align: center;
      }
      
      .newsletter {
         background: linear-gradient(45deg, #4a90e2, #357abd);
         padding: 3rem;
         text-align: center;
         color: #fff;
         margin: 3rem 0;
      }
      
      .newsletter input[type="email"] {
         padding: 1rem;
         width: 300px;
         border: none;
         border-radius: 5px;
         margin-right: 1rem;
      }
      
      .category-icon {
         transition: transform 0.3s ease;
      }
      
      .category-icon:hover {
         transform: scale(1.1);
      }

      .home-bg {
         background: linear-gradient(135deg, #4f8cff 0%, #6a82fb 50%, #fc5c7d 100%);
         background-size: cover;
         background-position: center;
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- Special Offer Banner -->
<div class="special-offer" data-aos="fade-up">
   <h2>Special Summer Sale!</h2>
   <div class="countdown">
      <div>
         <span id="days">00</span>
         <p>Days</p>
      </div>
      <div>
         <span id="hours">00</span>
         <p>Hours</p>
      </div>
      <div>
         <span id="minutes">00</span>
         <p>Minutes</p>
      </div>
      <div>
         <span id="seconds">00</span>
         <p>Seconds</p>
      </div>
   </div>
   <a href="shop.php" class="btn">Shop Now</a>
</div>

<div class="home-bg">

<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-1.png" alt="">
         </div>
         <div class="content">
            <span>Upto 50% Off</span>
            <h3>Latest Smartphones</h3>
            <a href="category.php?category=smartphone" class="btn">Shop Now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-2.png" alt="">
         </div>
         <div class="content">
            <span>Upto 50% off</span>
            <h3>Latest Watches</h3>
            <a href="category.php?category=watch" class="btn">Shop Now.</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-3.png" alt="">
         </div>
         <div class="content">
            <span>upto 50% off</span>
            <h3>Latest headsets</h3>
            <a href="shop.php" class="btn">Shop Now.</a>
         </div>
      </div>

   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">Shop by Category</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <a href="category.php?category=laptop" class="swiper-slide slide">
      <img src="images/icon-1.png" alt="">
      <h3>Laptop</h3>
   </a>

   <a href="category.php?category=tv" class="swiper-slide slide">
      <img src="images/icon-2.png" alt="">
      <h3>Television</h3>
   </a>

   <a href="category.php?category=camera" class="swiper-slide slide">
      <img src="images/icon-3.png" alt="">
      <h3>Camera</h3>
   </a>

   <a href="category.php?category=mouse" class="swiper-slide slide">
      <img src="images/icon-4.png" alt="">
      <h3>Mouse</h3>
   </a>

   <a href="category.php?category=fridge" class="swiper-slide slide">
      <img src="images/icon-5.png" alt="">
      <h3>Fridge</h3>
   </a>

   <a href="category.php?category=washing" class="swiper-slide slide">
      <img src="images/icon-6.png" alt="">
      <h3>Washing machine</h3>
   </a>

   <a href="category.php?category=smartphone" class="swiper-slide slide">
      <img src="images/icon-7.png" alt="">
      <h3>Smartphone</h3>
   </a>

   <a href="category.php?category=watch" class="swiper-slide slide">
      <img src="images/icon-8.png" alt="">
      <h3>Watch</h3>
   </a>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">Latest products</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>Nrs.</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<!-- Testimonials Section -->
<section class="testimonials">
   <h1 class="heading">What Our Customers Say</h1>
   <div class="swiper testimonial-slider">
      <div class="swiper-wrapper">
         <div class="swiper-slide testimonial-card" data-aos="fade-up">
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
            </div>
            <p>"Amazing products and excellent service! Will definitely shop again."</p>
            <h3>John Doe</h3>
         </div>
         <div class="swiper-slide testimonial-card" data-aos="fade-up" data-aos-delay="200">
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <p>"Fast delivery and great quality products. Very satisfied!"</p>
            <h3>Jane Smith</h3>
         </div>
      </div>
      <div class="swiper-pagination"></div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="js/script.js"></script>

<script>
   // Initialize AOS
   AOS.init({
      duration: 1000,
      delay: 200
   });

   // Set the end date for the countdown (e.g., 7 days from now)
   const endDate = new Date();
   endDate.setDate(endDate.getDate() + 7);

   function updateCountdown() {
      const now = new Date().getTime();
      const distance = endDate - now;

      const days = Math.floor(distance / (1000 * 60 * 60 * 24));
      const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      const seconds = Math.floor((distance % (1000 * 60)) / 1000);

      document.getElementById('days').innerHTML = days.toString().padStart(2, '0');
      document.getElementById('hours').innerHTML = hours.toString().padStart(2, '0');
      document.getElementById('minutes').innerHTML = minutes.toString().padStart(2, '0');
      document.getElementById('seconds').innerHTML = seconds.toString().padStart(2, '0');
   }

   setInterval(updateCountdown, 1000);
   updateCountdown();

   // Initialize Swiper for testimonials
   var testimonialSwiper = new Swiper(".testimonial-slider", {
      loop: true,
      spaceBetween: 20,
      pagination: {
         el: ".swiper-pagination",
         clickable: true,
      },
      breakpoints: {
         0: {
            slidesPerView: 1,
         },
         768: {
            slidesPerView: 2,
         },
      },
   });

   var swiper = new Swiper(".home-slider", {
      loop:true,
      spaceBetween: 20,
      pagination: {
         el: ".swiper-pagination",
         clickable:true,
      },
   });

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>