<?php
session_start();

$servername = "localhost";
$username = "root";		
$password = "12345678";		
$dbname = "rsshop";		
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	
	die("Connection failed: " . $conn->connect_error);	
}

if (isset($_POST['add_to_cart'])) {
	$item_id = intval($_POST['item_id']);
	$item_name = $_POST['item_name'];
	$item_price = floatval($_POST['item_price']);
	$quantity = intval($_POST['quantity']);
	
		if ($quantity < 1) {
		$quantity = 1;
	}

		if (!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = [];
	}

	$image_query = "SELECT image FROM items WHERE id = $item_id";
	$image_result = $conn->query($image_query);
	$item_image = 'default.png'; 

	if ($image_result && $image_result->num_rows > 0) {
		$item_data = $image_result->fetch_assoc();
		$item_image = $item_data['image'];
	}
	
	if (isset($_SESSION['cart'][$item_id])) {
		$_SESSION['cart'][$item_id]['quantity'] += $quantity;
	} else {
		$_SESSION['cart'][$item_id] = [
			'id' => $item_id,
			'name' => $item_name,
			'price' => $item_price,
			'quantity' => $quantity,
			'image' => $item_image // * تم إضافة حقل الصورة هنا *
		];
	}
	
	$_SESSION['message'] = $item_name . " added to cart (x" . $quantity . ")!";
	
           $redirect_url = "home.php";
    if ($item_id) {
        $anchor = '#item-card-' . $item_id; 
        $redirect_url .= $anchor;
    }
    
	header("Location: " . $redirect_url);
	exit();
}

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
	<title>Home | RS Shop</title>
	
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

		.success-message {
			background-color: #d4edda;		
			color: #155724;	
			padding: 15px;		
			text-align: center;
			border-radius: 5px;
			margin: 20px 40px 0;
			font-weight: bold;
		}
	 .rs-page-content {
			padding: 20px 40px;
			text-align: center;
		}
		
		.about-container {
			padding: 60px 80px;
			text-align: center;
			max-width: 1000px;
			margin: 40px auto 0;
			background-color: var(--white);	
			border-radius: 12px 12px 0 0;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
			position: relative;
		}
		
		.about-container h2 { color: var(--primary-pink); font-size: 36px; margin-bottom: 20px; border-bottom: 3px solid var(--primary-pink); display: inline-block; padding-bottom: 10px; }
		.about-container p { font-size: 19px; line-height: 1.8; color: #444; margin-bottom: 30px; }
		.about-highlights { display: flex; justify-content: space-around; gap: 20px; margin-top: 40px; }
		.highlight-item { flex: 1; padding: 25px; background-color: var(--light-pink); border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); transition: transform 0.3s; }
		.highlight-item:hover { transform: translateY(-5px); }
		.highlight-item i { font-size: 45px; color: var(--primary-pink); margin-bottom: 15px; }
		.highlight-item h3 { color: var(--btn-hover-color); font-size: 22px; margin-top: 0; }

		.footer-wave-separator {
			width: 100%;
			height: 50px;		
			overflow: hidden;		
			background-color: var(--white);		
			position: relative;
		}
		
		.footer-wave-separator svg {
			display: block;
			width: 100%;
			height: 100px;
			position: absolute;
			top: -50px;		
		}
		
		.footer-wave-separator .wave {
			fill: none;
			stroke: var(--primary-pink);		
			stroke-width: 12;		
		}


                 		.our-service-section {
			background-color: var(--white);		
			padding: 10px 0 60px;		
			text-align: center;
		}
		.our-service-section h2 {
			font-size: 30px;
			margin: 0;
			color: var(--primary-pink);		
		}

                		.category-title-service {
			color: var(--primary-pink);
			font-size: 32px;
			margin: 40px auto 20px;
			text-align: center;
			border-bottom: 3px solid var(--primary-pink);
			display: inline-block;
			padding-bottom: 5px;
			width: 80%;		
			max-width: 1200px;
		}

		.category-container {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			gap: 25px;
			padding: 20px 0;
			margin: 0 auto;
			max-width: 1300px;
		}

		.item-card {
			background-color: var(--main-bg); 
			                        border: 1px solid var(--light-pink);
			padding: 15px;
			text-align: center;
			width: 280px;
			box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
			border-radius: 12px;
			transition: transform 0.3s ease;
		}

		.item-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
		}

		               .item-card img {
			width: 100%;
			height: 120px; 
			                        object-fit: contain; 
			                        border-radius: 8px;
			margin-bottom: 10px;
		}
		.item-card h3 {
			color: #333;
			font-size: 20px;
			margin: 5px 0;
		}

		.item-card p strong {
			color: var(--primary-pink);
			font-size: 1.1em;
		}

		.quantity-controls {
			display: flex;
			justify-content: center;
			align-items: center;
			margin: 10px 0;
		}

		.quantity-controls button {
			background-color: var(--light-pink);
			color: var(--primary-pink);
			border: 1px solid var(--primary-pink);
			padding: 5px 10px;
			cursor: pointer;
			font-size: 18px;
			width: 35px;
			height: 35px;
			line-height: 1;
			border-radius: 4px;
			transition: background-color 0.3s;
		}

		.quantity-controls input {
			width: 40px;
			text-align: center;
			border: 1px solid #ddd;
			padding: 5px 0;
			margin: 0 5px;
			font-size: 16px;
			height: 35px;
			-moz-appearance: textfield;	
		}
		.quantity-controls input::-webkit-outer-spin-button,
		.quantity-controls input::-webkit-inner-spin-button {
			-webkit-appearance: none;	
			margin: 0;
		}

		.add-to-cart-btn {
			background-color: var(--primary-pink);
			color: var(--white);
			border: none;
			padding: 10px 20px;
			cursor: pointer;
			border-radius: 20px;
			font-weight: bold;
			transition: background-color 0.3s ease;
			width: 100%;
			margin-top: 10px;
		}
		.add-to-cart-btn:hover {
			background-color: var(--btn-hover-color);
		}

		.contact-section {
			background-color: var(--primary-pink);
			color: var(--white);
			padding: 60px 40px;
			text-align: center;
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
			color: #FFD700; 		}

	</style>
</head>
<body>

<nav class="rs-navbar">
	<a href="home.php" class="rs-logo-link">
		<span class="rs-logo">RS Shop</span>
	</a>
	<ul class="rs-nav-links">
		<li><a href="about.php">About</a></li>	
		<li><a href="#contact-section">Contact</a></li>
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

<?php if (isset($_SESSION['message'])): ?>
	<div class="success-message">
		<i class="fas fa-check-circle"></i> <?= $_SESSION['message']; unset($_SESSION['message']); ?>
	</div>
<?php endif; ?>


<div class="rs-page-content">
	<h1>Welcome to RS Shop! 💗</h1>
	<p style="font-size: 1.2em; color: #666; margin-bottom: 0;">We are thrilled to have you here. Explore our sections below!</p>
</div>

<div class="about-container" id="about-section">
	<h2>About Our Shop! 💖</h2>
	
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
<div class="footer-wave-separator">
	<svg viewBox="0 0 1440 100" preserveAspectRatio="none">
		<path class="wave" d="M0,70 C240,150 720,-50 1440,70"></path>
	</svg>
</div>

<div class="our-service-section">
	<h2>Our Services</h2>
	<p style="color: #666;">Explore our wide selection of delicious foods and beverages.</p>

	<?php
	if ($conn->connect_error) {
		echo '<p style="text-align: center; padding: 50px; color: red;">Database connection failed. Please check credentials.</p>';
	} else {

		$categories_query = "SELECT id, name FROM categories ORDER BY id";
		$categories_result = $conn->query($categories_query);

		if ($categories_result && $categories_result->num_rows > 0) {
			
			while($category = $categories_result->fetch_assoc()) {
				$category_id = $category['id'];
				$category_name = htmlspecialchars($category['name']);

				echo '<center><h3 class="category-title-service">' . $category_name . '</h3></center>';
				echo '<div class="category-container">';

				$items_query = "SELECT id, name, price, image FROM items WHERE category_id = $category_id ORDER BY name";
				$items_result = $conn->query($items_query);

				if ($items_result && $items_result->num_rows > 0) {
					
					while($item = $items_result->fetch_assoc()) {
						$item_db_id = $item['id']; 
						$item_name = htmlspecialchars($item['name']);	
						$item_price_display = number_format($item['price'], 2); 
						$item_price_raw = $item['price']; 
						$item_image = htmlspecialchars($item['image']);	
						$item_id_safe = 'item_' . $item_db_id; 
							
						echo '
						<div class="item-card" id="item-card-' . $item_db_id . '">
							<img src="pict2/' . $item_image . '" alt="' . $item_name . '">
							<h3>' . $item_name . '</h3>
							
							<p><strong>Price: ' . $item_price_display . ' JOD</strong></p>

							<form action="home.php" method="post">
								
								<input type="hidden" name="item_id" value="' . $item_db_id . '">
								<input type="hidden" name="item_name" value="' . $item_name . '">
								<input type="hidden" name="item_price" value="' . $item_price_raw . '">

								<div class="quantity-controls">
									<button type="button" onclick="changeQuantity(\'' . $item_id_safe . '\', -1)">-</button>
									<input type="number" name="quantity" id="' . $item_id_safe . '_Qty" value="1" min="1" readonly>
									<button type="button" onclick="changeQuantity(\'' . $item_id_safe . '\', 1)">+</button>
								</div>

								<button type="submit" name="add_to_cart" class="add-to-cart-btn">
									<i class="fas fa-cart-plus"></i> Add to Cart
								</button>
							</form>
						</div>';
					}
				} else {
					echo '<p style="text-align: center; width: 100%; color: #666;">No items available in ' . $category_name . '.</p>';
				}

				echo '</div>'; 
			}
		} else {
			echo '<p style="text-align: center; padding: 50px;">No categories to display. Check your database tables.</p>';
		}

		$conn->close();
	}
	?>
</div>

<div class="contact-section" id="contact-section">
	<h2>Contact Us</h2>
	<p>We are ready to serve you! You can reach us via phone or visit our location.</p>
	
	<div class="contact-info">
		<div>
			<i class="fas fa-phone-alt"></i>
			<span>+962 77 123 4567</span>
		</div>
		<div>
			<i class="fas fa-map-marker-alt"></i>
			<span>Amman, Jordan</span>
		</div>
	</div>
	
	<p style="font-size: 16px; margin-top: 40px; color: #f2f2f2;">RS Shop &copy; 2025</p>
</div>


<script>
		function changeQuantity(itemIdSafe, delta) {
		var inputElement = document.getElementById(itemIdSafe + '_Qty');
		
		if (inputElement) {
			var currentValue = parseInt(inputElement.value);
			var newValue = currentValue + delta;
			
			if (newValue >= 1) {
				inputElement.value = newValue;
			}
		}
	}
	
</script>
</body>
</html>