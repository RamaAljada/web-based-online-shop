<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "12345678"; 
$db   = "rsshop";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_POST['email'];
$password = $_POST['password'];

// Check user
$sql = "SELECT * FROM shop WHERE email='$email'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if (password_verify($password, $row['password'])) {
        $_SESSION['user'] = $row['username'];
        header("Location: home.php");
        exit();
    } else {
        header("Location: Login.html?error=Invalid email or password! Please try again.");
        exit();
    }
} else {
    header("Location: Login.html?error=Invalid email or password! Please try again.");
    exit();
}

mysqli_close($conn);
?>
