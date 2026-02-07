<?php
$host = "localhost";
$user = "root";
$pass = "12345678"; 
$db   = "rsshop";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_POST['username'];
$email = $_POST['email'];
$country_code = $_POST['country_code'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

$check = mysqli_query($conn, "SELECT * FROM shop WHERE email='$email'");
if(mysqli_num_rows($check) > 0){
    header("Location: SignUp.html?error=Email already registered!");
    exit();
}

$sql = "INSERT INTO shop (username, email, country_code, phone, password)
        VALUES ('$username', '$email', '$country_code', '$phone', '$password')";

if (mysqli_query($conn, $sql)) {
    echo "Account created successfully! Redirecting to login...";
    header("refresh:2; url=Login.html"); 
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
