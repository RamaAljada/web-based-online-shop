 <?php
$conn = new mysqli("localhost", "root", "12345678", "shop");

if ($conn->connect_error) {
    die("Connection failed:" . $conn->connect_error);
}

$username = $_POST['username'];
$email = $_POST['email'];
$country_code = $_POST['country_code'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, phone, country_code, email, password)
        VALUES ('$username', '$phone', '$country_code', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "Account created successfully 🎉";
    header("refresh:2; url=Login.html"); 
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
