<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "12345678"; 
$db   = "rsshop";
$users_table = "shop"; 
$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    header("Location: login.html?error=" . urlencode("Database connection failed."));
    exit();
}

$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: login.html?error=" . urlencode("Email and password are required for deletion."));
    exit();
}

$sql_select = "SELECT password FROM $users_table WHERE email='$email'";
$result = mysqli_query($conn, $sql_select);

if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row['password'])) {

        $sql_delete = "DELETE FROM $users_table WHERE email='$email'";
        
        if (mysqli_query($conn, $sql_delete)) {
            session_unset();
            session_destroy();

            header("Location: login.html?success=" . urlencode("Your account has been successfully deleted."));
            exit();
        } else {
            header("Location: login.html?error=" . urlencode("Database error during deletion."));
            exit();
        }

    } else {
        header("Location: login.html?error=" . urlencode("Invalid password for deletion."));
        exit();
    }

} else {
    header("Location: login.html?error=" . urlencode("Email address not found."));
    exit();
}

mysqli_close($conn);
?>