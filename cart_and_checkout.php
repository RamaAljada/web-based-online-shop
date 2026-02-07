<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['item_id'])) {
	$item_id_to_remove = intval($_GET['item_id']);
	
	if (isset($_SESSION['cart'][$item_id_to_remove])) {
		unset($_SESSION['cart'][$item_id_to_remove]); 
		
		$_SESSION['message'] = "Product removed successfully.";
	}
	
	header("Location: cart_and_checkout.php?stage=cart");
	exit();
}

$current_stage = isset($_GET['stage']) ? $_GET['stage'] : 'cart';

if (isset($_GET['stage']) && $_GET['stage'] == 'checkout') {
		
$current_stage = 'checkout';
}


$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0;
$tax_rate = 0.16; 
$grand_total = 0;

foreach ($cart_items as $item) {
	$subtotal += $item['price'] * $item['quantity'];
}

$tax_amount = $subtotal * $tax_rate;
$grand_total = $subtotal + $tax_amount;

function get_cart_count($items) {
	$count = 0;
	foreach ($items as $item) {
		$count += $item['quantity'];
	}
	return $count;
}

$cart_total_count = get_cart_count($cart_items);

$currency_symbol = ' JOD'; 
function format_currency($amount, $symbol) {
	return number_format($amount, 2) . $symbol;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Cart & Checkout | RS Shop</title>
	
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
			display: flex; 
			flex-direction: column; 
			min-height: 100vh;
		}

		.rs-navbar {
			display: flex;
			justify-content: space-between; 
			align-items: center;
			background-color: var(--primary-pink); 
			padding: 10px 40px; 
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
			position: fixed; 
			top: 0;
			left: 0;
			right: 0;
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
		
		.cart-page-content {
			padding: 90px 40px 40px 40px; 
			flex-grow: 1;
			display: flex;
			justify-content: center;
			align-items: flex-start;
		}

		.main-content {
			width: 60%;
			background-color: var(--white);
			padding: 30px;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
			border-radius: 12px;
			min-height: 400px;
		}

		.cart-sidebar {
			width: 30%;
			background-color: var(--white);
			margin-left: 20px;
			padding: 30px;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
			border-radius: 12px;
			position: sticky; 
			top: 90px;
		}
		
		.cart-item {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 15px 0;
			border-bottom: 1px solid #eee;
		}
		
		.cart-item-info {
			display: flex;
			align-items: center;
			flex-grow: 1;
		}

		.cart-item img {
			width: 60px; 			
                                                height: 60px;
			object-fit: cover; 
			border-radius: 8px;
			margin-inline-end: 15px;			
                                               border: 1px solid #eee;
		}
		
		.cart-item-details {
			flex-grow: 1;
			text-align: left;
		}
		.cart-item-details h4 {
			margin: 0 0 5px 0;
			color: var(--dark-gray);
			font-size: 1.1em;
		}
		.cart-item-details p {
			margin: 0;
			color: #666;
			font-size: 0.9em;
		}
		.cart-item-price {
			font-weight: bold;
			color: var(--primary-pink);
			font-size: 1em;
			display: flex;
			align-items: center;
		}
		
		.remove-btn {
			color: #ccc;
			margin-left: 15px;
			cursor: pointer;
			transition: color 0.3s;
			font-size: 1.2em;
		}
		.remove-btn:hover {
			color: red;
		}

		.checkout-btn {
			background-color: var(--primary-pink);
			color: var(--white);
			border: none;
			padding: 12px 25px;
			border-radius: 25px;
			font-size: 18px;
			font-weight: bold;
			cursor: pointer;
			width: 100%;
			margin-top: 20px;
			transition: background-color 0.3s;
		}
		.checkout-btn:hover {
			background-color: var(--btn-hover-color);
		}

		.summary-row {
			display: flex;
			justify-content: space-between;
			padding: 8px 0;
			font-size: 1em;
			color: var(--dark-gray);
		}
		.summary-total {
			font-size: 1.2em;
			font-weight: bold;
			color: var(--btn-hover-color);
			border-top: 2px solid var(--primary-pink);
			padding-top: 15px;
			margin-top: 15px;
		}
		
		.empty-cart {
			text-align: center;
			padding: 50px;
		}
		.empty-cart i {
			font-size: 50px;
			color: #ccc;
			margin-bottom: 15px;
		}
		.empty-cart p {
			font-size: 1.2em;
			color: #888;
		}
		
		.confirmation-box {
			max-width: 700px;
			margin: 150px auto 50px; 
			padding: 40px;
			background-color: var(--white);
			border-radius: 12px;
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
			text-align: center;
		}
		.confirmation-box i {
			font-size: 60px;
			color: var(--primary-pink);
			margin-bottom: 20px;
		}
		.confirmation-box h1 {
			color: var(--btn-hover-color);
			font-size: 32px;
			margin-top: 10px;
		}
		.confirmation-box p {
			font-size: 1.3em;
			line-height: 1.7;
			color: #333;
			margin: 20px 0;
			font-weight: 500;
		}
		.back-to-home-btn {
			display: inline-block;
			margin-top: 30px;
			padding: 12px 30px;
			background-color: var(--primary-pink);
			color: white;
			text-decoration: none;
			border-radius: 25px;
			font-size: 18px;
			font-weight: bold;
			transition: background-color 0.3s;
		}
		.back-to-home-btn:hover {
			background-color: var(--btn-hover-color);
		}

	</style>
</head>
<body>

<nav class="rs-navbar">
	<a href="home.php" class="rs-logo-link">
		<span class="rs-logo">RS Shop</span>
	</a>
	<ul class="rs-nav-links">
		<li><a href="about.php">About</a></li>
		<li><a href="contact.php">Contact</a></li>
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

<?php if ($current_stage == 'cart'): ?>
	
	<div class="cart-page-content">
		
		<div class="main-content">
			<h1>Your Shopping Cart</h1>
			
			<?php if (isset($_SESSION['message'])): ?>
				<div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
					<?= $_SESSION['message']; unset($_SESSION['message']); ?>
				</div>
			<?php endif; ?>
			
			<?php if (!empty($cart_items)): ?>
				
				<p style="color: #666; font-size: 1.1em;">Review your delicious items before checkout.</p>
				
				<div class="cart-items-list">
					<?php foreach ($cart_items as $item_id => $item): ?>
						<div class="cart-item">
							 
							<div class="cart-item-info">
								<?php 
                                                                                                                              $image_src = isset($item['image']) ? $item['image'] : 'default.png';
								?>
								<img src="pict2/<?= htmlspecialchars($image_src) ?>" alt="<?= htmlspecialchars($item['name']) ?>" title="<?=               htmlspecialchars($item['name']) ?>">
								 
								<div class="cart-item-details">
									<h4><?= htmlspecialchars($item['name']) ?></h4>
									<p>Quantity: <?= $item['quantity'] ?> x <?= format_currency($item['price'], $currency_symbol) ?></p>
								</div>
							</div>
							<div class="cart-item-price">
								<?= format_currency($item['price'] * $item['quantity'], $currency_symbol) ?>
								<a href="cart_and_checkout.php?action=remove&item_id=<?= $item_id ?>" class="remove-btn" title="Remove Item">
									<i class="fas fa-times-circle"></i>
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				
			<?php else: ?>
				<div class="empty-cart">
					<i class="fas fa-box-open"></i>
					<h2>Your Cart is Empty</h2>
					<p>Looks like you haven't added anything to your cart yet.</p>
					<a href="home.php" class="checkout-btn" style="width: 50%; margin-top: 30px;">Back to Home</a>
				</div>
			<?php endif; ?>

		</div>

		<div class="cart-sidebar">
			<h3>Order Summary</h3>
			
			<div class="summary-details">
				<div class="summary-row">
					<span>Subtotal (<?= $cart_total_count ?> items)</span>
					<span><?= format_currency($subtotal, $currency_symbol) ?></span>
				</div>
				
				<div class="summary-row">
					<span>Tax (<?= $tax_rate * 100 ?>%)</span>
					<span><?= format_currency($tax_amount, $currency_symbol) ?></span>
				</div>
				
				<div class="summary-row summary-total">
					<span>Grand Total</span>
					<span><?= format_currency($grand_total, $currency_symbol) ?></span>
				</div>
			</div>
			
			<h4 style="margin-top: 30px;">Payment Method</h4>
			<select style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
				<option>Cash on Delivery</option>
				<option disabled>Credit/Debit Card (Coming Soon)</option>
			</select>

			<?php if (!empty($cart_items)): ?>
				<a href="cart_and_checkout.php?stage=checkout" style="text-decoration: none;">
					<button class="checkout-btn">Proceed to Checkout</button>
				</a>
			<?php endif; ?>
			
			<a href="home.php" style="display: block; text-align: center; margin-top: 15px; color: var(--primary-pink);">Continue Shopping</a>
		</div>

	</div>

<?php elseif ($current_stage == 'checkout'): ?>

	<div class="confirmation-box">
		<i class="fas fa-hand-holding-heart"></i>
		<h1>Order Confirmed!</h1>
		
		<p>
			Thank you for your order!
			<br>
			Your order is being prepared. Please visit our store to complete payment and pick up your order.
			<br>
			We look forward to serving you soon!
		</p>
		
		<a href="home.php" class="back-to-home-btn">Back to Home</a>
	</div>

<?php endif; ?>

</body>
</html>