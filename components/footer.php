<?php
// Add PHP code for newsletter subscription if needed
if(isset($_POST['subscribe_email'])) {
    // Handle newsletter subscription
    $email = filter_var($_POST['subscribe_email'], FILTER_SANITIZE_EMAIL);
    // Add your subscription logic here
}
?>

<footer class="footer">

   <section class="grid">

      <div class="box">
         <h3>Quick links</h3>
         <a href="home.php"> <i class="fas fa-angle-right"></i> Home</a>
         <a href="about.php"> <i class="fas fa-angle-right"></i> About</a>
         <a href="shop.php"> <i class="fas fa-angle-right"></i> Shop</a>
         <a href="contact.php"> <i class="fas fa-angle-right"></i> Contact</a>
      </div>

      <div class="box">
         <h3>Extra links</h3>
         <a href="user_login.php"> <i class="fas fa-angle-right"></i> Login</a>
         <a href="user_register.php"> <i class="fas fa-angle-right"></i> Register</a>
         <a href="cart.php"> <i class="fas fa-angle-right"></i> Cart</a>
         <a href="orders.php"> <i class="fas fa-angle-right"></i> Orders</a>
      </div>

      <div class="box">
         <h3>Contact Us</h3>
         <a href="tel:9800000000"><i class="fas fa-phone"></i> +977 980 000 0000</a>
         <a href="tel:9900000000"><i class="fas fa-phone"></i> +977 974 000 0000</a>
         <a href="mailto:Electronicgdts@gmail.com"><i class="fas fa-envelope"></i>Electronicgdts@edu.np</a>
         <a href="https://maps.google.com/maps/@27.646944,85.306111,17z"><i class="fas fa-map-marker-alt"></i> Kathmandu, Nepal </a>
      </div>

      <div class="box">
         <h3>Newsletter</h3>
         <form action="" method="post" class="newsletter-form">
            <input type="email" name="subscribe_email" placeholder="Enter your email" required>
            <button type="submit" class="fas fa-paper-plane"></button>
         </form>
         <div class="social-links">
            <h3>Follow Us</h3>
            <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin"></i></a>
         </div>
      </div>

   </section>

   <style>
   .newsletter-form {
      display: flex;
      gap: 10px;
      margin-bottom: 15px;
   }
   
   .newsletter-form input[type="email"] {
      flex: 1;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
   }
   
   .newsletter-form button {
      background: #357abd;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s ease;
   }
   
   .newsletter-form button:hover {
      background: #2868a5;
   }
   
   .social-links {
      margin-top: 15px;
   }
   
   .social-links a {
      display: inline-block;
      margin-right: 10px;
      font-size: 18px;
      color: #666;
      transition: color 0.3s ease;
   }
   
   .social-links a:hover {
      color: #357abd;
   }
   
   .social-links h3 {
      margin-bottom: 10px;
      font-size: 16px;
   }
   </style>

   <div class="credit">&copy; copyright @ <?= date('Y'); ?> by <span>Electronicgdts@gmail.com</span> | all rights reserved!</div>

</footer>