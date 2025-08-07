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
   <title>About Us - ProjectDone</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .about-section {
         max-width: 900px;
         margin: 40px auto;
         background: #fff;
         border-radius: 10px;
         box-shadow: 0 2px 8px rgba(0,0,0,0.08);
         padding: 40px 30px;
      }
      .about-section h1 {
         color: #333;
         margin-bottom: 20px;
         font-size: 2.5rem;
         text-align: center;
      }
      .about-section h2 {
         color: #007bff;
         margin-top: 30px;
         font-size: 1.5rem;
      }
      .about-section p {
         color: #444;
         font-size: 1.1rem;
         line-height: 1.7;
         margin-bottom: 18px;
      }
      .about-section ul {
         margin-left: 20px;
         color: #444;
         font-size: 1.1rem;
      }
      .about-section .cta {
         display: block;
         margin: 30px auto 0 auto;
         padding: 12px 32px;
         background: #007bff;
         color: #fff;
         border: none;
         border-radius: 5px;
         font-size: 1.1rem;
         cursor: pointer;
         text-align: center;
         text-decoration: none;
         transition: background 0.2s;
         width: fit-content;
      }
      .about-section .cta:hover {
         background: #0056b3;
      }
   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about-section">
   <h1 style="color:#007bff; font-weight:bold; font-size:2.7rem; margin-bottom:10px;">
      Discover the Joy of Shopping!
   </h1>
   <p style="font-size:1.2rem; color:#222;">
      <img src="images/gadget-store.jpg" alt="Gadget Store" style="float:right; width:180px; margin:0 0 20px 30px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
      Welcome to <span style="color:#007bff; font-weight:bold;">Electronic Gadget Store</span>!<br>
      <span style="color:#28a745; font-weight:bold;">Your one-stop shop for the latest electronics and everyday essentials.</span>
      <br><br>
      At Electronic Gadget Store, our mission is to make online shopping <span style="color:#007bff;">simple</span>, <span style="color:#007bff;">fast</span>, and <span style="color:#007bff;">enjoyable</span>. Explore a wide range of products, from cutting-edge gadgets to must-have accessories, all in one place.
   </p>
   <h2 style="margin-top:35px;">Why You'll Love Shopping With Us</h2>
   <ul style="list-style:none; padding:0;">
      <li style="margin-bottom:12px;">
         <i class="fas fa-search" style="color:#007bff; margin-right:8px;"></i>
         <strong>Smart Search:</strong> Find products instantly—even if you only remember the first letter!
      </li>
      <li style="margin-bottom:12px;">
         <i class="fas fa-shield-alt" style="color:#28a745; margin-right:8px;"></i>
         <strong>Secure Shopping:</strong> Your privacy and security are our top priorities.
      </li>
      <li style="margin-bottom:12px;">
         <i class="fas fa-smile" style="color:#ffc107; margin-right:8px;"></i>
         <strong>Customer Focused:</strong> We listen to your feedback and strive to make your experience the best.
      </li>
      <li style="margin-bottom:12px;">
         <i class="fas fa-sync-alt" style="color:#17a2b8; margin-right:8px;"></i>
         <strong>Always Improving:</strong> We're always updating our platform to serve you better.
      </li>
   </ul>
   <div style="background:#f8f9fa; border-radius:8px; padding:18px 20px; margin:30px 0 20px 0; border-left:5px solid #007bff;">
      <strong style="color:#007bff;">Our Promise:</strong> <span style="color:#444;">We guarantee 100% satisfaction with every purchase. If you're not happy, we'll make it right!</span>
   </div>
   <h2>Our Vision</h2>
   <p style="font-size:1.1rem;">
      To become your favorite online destination for electronics by offering a seamless, reliable, and enjoyable shopping experience.
   </p>
   <a href="shop.php" class="cta" style="margin-top:20px;">Start Shopping</a>
   <a href="contact.php" class="cta" style="background:#28a745; margin-top:10px;">Contact Us</a>
</section>

<section class="reviews">
   
   <h1 class="heading">Client's Reviews.</h1>

   <div class="swiper reviews-slider">

   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <img src="images/pic-5.jpg" alt="">
         <p>Been using their services for quite a bit and have never had an issue with the quality of their products. Online e-products working great as well. Only issue I have is they usually deliver when I'm a little caught up, though I've set a preferred delivery time. Everything else has been good.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3> <a href="https://www.facebook.com/profile.php?id=100083292714419" target="_blank">Denisha </a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-1.jpg" alt="">
         <p>It is the first online services in Nepal which we can trust completely.I always unbox making a video and instantly complain if there's anything wrong. Sometimes even don't need to return the item and they process the refund.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3><a href="https://www.facebook.com/profile.php?id=100075602340579" target="_blank">Rushab Risal</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-3.jpg" alt="">
         <p>Electronic Gadget Store is great if you choose good sellers . A variety of required item available . Customers can return and refund full amount within 7 days easily .It provides great opportunity to sale items online with ease.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3><a href="https://www.facebook.com/kaushalsah135790" target="_blank">Kaushal Shah</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-7.jpg" alt="">
         <p>Using Electronic Gadget Store for online shopping from almost 3 years. Outstanding experience with them. Game vouchers and pick up point as delivery with 0 shipping charges are super saving services.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3><a href="https://www.facebook.com/fuccheekta.moh.1" target="_blank">Subash Ray</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-2.jpg" alt="">
         <p>I have been using their services for the last 2 years and I have found them extremely reliable.Their return policy is what gives you an extra layer of reliance and peace of mind. In case the product doesn't meet your expectations or if there is any fault in it. then you can return the product within seven days from the date of delivery.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3><a href="https://www.facebook.com/ranjitchaudhary159" target="_blank">Ranjit Chaudhary</a></h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-6.jpg" alt="">
         <p> I have ordered hundreds of products from it and never got any scam. It delivers products in time with out delay. Packaging of products are strong and delivery rates are too low. Just amazing Website will keep shopping from Electronic Gadget store</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3><a href="https://www.facebook.com/pra.x.nil"  target="_blank">Pranil Poudel</a></h3>
      </div>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
        slidesPerView:1,
      },
      768: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>