<?php
session_start();

$cart_total_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_total_count += $item['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | RS Shop</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
               :root {
            --primary-pink: #E64980; 
            --text-color: #FFFFFF;
            --btn-hover-color: #C7356F; 
            --light-pink: #FFF0F5;
            --main-bg: #f8f8f8;
            --white: #ffffff;
            --dark-gray: #444; 
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: var(--main-bg); 
            background-image: url('background.png'); 
            background-repeat: repeat;
            background-attachment: fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

          .rs-navbar {
            display: flex;
            justify-content: space-between; 
            align-items: center;
            background-color: var(--primary-pink); 
            padding: 10px 40px; 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .rs-logo-link { text-decoration: none; color: var(--text-color); margin-right: 50px; }
        .rs-logo { font-size: 28px; font-weight: bold; color: var(--text-color); letter-spacing: 1px; display: block; }
        .rs-nav-links { list-style: none; display: flex; margin: 0; padding: 0; flex-grow: 1; align-items: center; }
        .rs-nav-links li { margin: 0 15px; }
        .rs-nav-links a { text-decoration: none; color: var(--text-color); font-size: 18px; font-weight: 500; padding: 5px 0; transition: color 0.3s ease; }
        .rs-nav-links a:hover { color: #F8F8F8; }
        .rs-actions { display: flex; align-items: center; }
        .rs-cart-icon a { position: relative; color: var(--text-color); font-size: 28px; text-decoration: none; margin-right: 25px; }
        .rs-cart-count { 
            position: absolute; top: -5px; right: -10px; background-color: #FFD700; color: var(--primary-pink); 
            border-radius: 50%; padding: 2px 6px; font-size: 12px; font-weight: bold; line-height: 1; 
        }
        .rs-logout-btn { background-color: var(--text-color); color: var(--primary-pink); padding: 8px 20px; border-radius: 20px; text-decoration: none; font-size: 16px; font-weight: bold; transition: background-color 0.3s ease, color 0.3s ease; border: none; cursor: pointer; }
        .rs-logout-btn:hover { background-color: var(--btn-hover-color); color: var(--text-color); }
        
        
        .about-page-container {
            flex-grow: 1;
            padding: 90px 40px 40px 40px; 
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .about-content-box {
            max-width: 900px;
            background-color: var(--white);
            padding: 50px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .about-content-box h1 { 
            color: var(--primary-pink); 
            font-size: 42px; 
            margin-bottom: 25px; 
            border-bottom: 4px solid var(--primary-pink); 
            display: inline-block; 
            padding-bottom: 10px; 
        }
        
        .about-content-box p { 
            font-size: 20px; 
            line-height: 1.8; 
            color: #444; 
            margin-bottom: 30px; 
            text-align: justify;
        }

        .about-highlights { 
            display: flex; 
            justify-content: space-around; 
            gap: 20px; 
            margin-top: 40px; 
        }
        
        .highlight-item { 
            flex: 1; 
            padding: 25px; 
            background-color: var(--light-pink); 
            border-radius: 10px; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); 
            transition: transform 0.3s; 
        }
        .highlight-item:hover { 
            transform: translateY(-5px); 
        }
        .highlight-item i { 
            font-size: 45px; 
            color: var(--primary-pink); 
            margin-bottom: 15px; 
        }
        .highlight-item h3 { 
            color: var(--btn-hover-color); 
            font-size: 22px; 
            margin-top: 0; 
        }

        .contact-section {
            background-color: var(--primary-pink);
            color: var(--white);
            padding: 60px 40px;
            text-align: center;
              margin-top: 50px; 
        }
        .contact-section h2 {
            font-size: 32px;
            margin-bottom: 30px;
            border-bottom: 3px solid var(--white);
            display: inline-block;
            padding-bottom: 10px;
        }
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 50px;
            font-size: 20px;
            margin-top: 20px;
            direction: ltr; 
        }
        .contact-info div {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .contact-info i {
            font-size: 28px;
            color: #FFD700; 
        }
        .rs-footer {
            background-color: var(--primary-pink);
            color: var(--white);
            text-align: center;
            padding: 15px 0;
            font-size: 16px;
        }

    </style>
</head>
<body>

<nav class="rs-navbar">
    <a href="home.php" class="rs-logo-link">
        <span class="rs-logo">RS Shop</span>
    </a>
    <ul class="rs-nav-links">
        <li><a href="home.php#about-section">About</a></li> 
        
        <li><a href="home.php#contact-section">Contact</a></li>
        
    </ul>
    <div class="rs-actions">
        <div class="rs-cart-icon">
            <a href="cart_and_checkout.php?stage=cart">
                <i class="fas fa-shopping-cart"></i>
                <span class="rs-cart-count"><?= $cart_total_count ?></span>
            </a>
        </div>
        <a href="logout.php" class="rs-logout-btn">Logout</a>
    </div>
</nav>


<div class="about-page-container">
    <div class="about-content-box">
        <h1>About Our Shop! 💖</h1>
        
        <p>
           At RS Shop, we offer a wide menu that suits different tastes and moods.
            Whether you are looking for something sweet, light, or refreshing, you will always find a good choice.
            Our menu is designed to be simple, enjoyable, and perfect for any time of the day.
            We focus on fresh preparation, good quality ingredients, and flavors that everyone can enjoy.<br>
            At RS Shop, we believe good taste brings people together and makes everyday moments better.
        </p>

        <div class="about-highlights">
            <div class="highlight-item">
                <i class="fas fa-cookie-bite"></i>
                <h3>Desserts</h3>
                <p>Delicious treats crafted to add a touch of sweetness to your day.</p>
            </div>
            <div class="highlight-item">
                <i class="fas fa-utensils"></i>
                <h3>Sandwiches</h3>
                <p>High-quality ingredients for all your special meals.</p>
            </div>
            <div class="highlight-item">
                <i class="fas fa-coffee"></i>
                <h3>Hot & Cold Drinks</h3>
                <p>Enjoy everything from warm specialty drinks to cool, refreshing sips.</p>
            </div>
        </div>
        
    </div>
</div>

<div class="rs-footer">
    RS Shop &copy; 2025
</div>

</body>
</html>