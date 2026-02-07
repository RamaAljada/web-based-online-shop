<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "12345678"; 
$db   = "rsshop";
$users_table = "shop"; 

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    
    if (!isset($_SESSION['user_to_update_id'])) {
        $error_message = "Session expired or access denied.";
    } else {
        $user_id = $_SESSION['user_to_update_id'];

        $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
        $phone = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
        $country_code = mysqli_real_escape_string($conn, $_POST['country_code'] ?? '');
        $new_password = $_POST['new_password'] ?? '';

        $update_fields = array();
        
        $update_fields[] = "username = '$username'";
        $update_fields[] = "phone = '$phone'";
        $update_fields[] = "country_code = '$country_code'";

        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_fields[] = "password = '$hashed_password'";
        }

        if (!empty($update_fields)) {
            $update_set = implode(", ", $update_fields);
            
            
            $sql_update = "UPDATE $users_table SET $update_set WHERE user_id = '$user_id'"; 

            if (mysqli_query($conn, $sql_update)) {
                $success_message = "Account successfully updated!";
                
                unset($_SESSION['user_to_update_email']);
                unset($_SESSION['user_to_update_id']);
                header("Location: login.html?success=" . urlencode("Account successfully updated. Please log in again."));
                exit();
            } else {
                $error_message = "Error updating record: " . mysqli_error($conn);
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql_select = "SELECT user_id, password FROM $users_table WHERE email='$email'";
    $result = mysqli_query($conn, $sql_select);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
                       $_SESSION['user_to_update_email'] = $email;
            $_SESSION['user_to_update_id'] = $row['user_id'];
        } else {
            header("Location: login.html?error=" . urlencode("Invalid password for update verification."));
            exit();
        }
    } else {
        header("Location: login.html?error=" . urlencode("Email address not found."));
        exit();
    }
}

if (isset($_SESSION['user_to_update_email'])) {
    
    $email_to_update = mysqli_real_escape_string($conn, $_SESSION['user_to_update_email']);
    $sql_fetch = "SELECT username, phone, country_code FROM $users_table WHERE email='$email_to_update'";
    $result_fetch = mysqli_query($conn, $sql_fetch);

    if ($result_fetch && $row = mysqli_fetch_assoc($result_fetch)) {
        $current_username = htmlspecialchars($row['username']);
        $current_phone = htmlspecialchars($row['phone']);
        $current_country_code = htmlspecialchars($row['country_code']);
    } else {
        $current_username = $current_phone = $current_country_code = '';
        $error_message = "Could not fetch current user details.";
    }
    
    mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update Account | RS Shop</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
body { font-family:'Tajawal', sans-serif; background: url('background.png') no-repeat center center fixed; background-size: cover; display:flex; justify-content:center; align-items:center; min-height:100vh; margin:0; }
.update-container { width:400px; background:white; padding:40px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.1); }
.update-container h2 { color:#A8407B; margin-bottom:20px; text-align:center; }
.input-group { margin-bottom:15px; }
input { width:100%; padding:10px; border-radius:10px; border:1px solid #F3D2DF; }
.update-btn { width:100%; padding:15px; background:#A8407B; color:white; border:none; border-radius:10px; cursor:pointer; font-size:16pt; }
.update-btn:hover { background:#8D3469; }
.error, .success { text-align:center; margin-bottom:15px; font-weight: bold; }
.error { color:red; }
.success { color:#28a745; }
label { display: block; margin-bottom: 5px; color: #555; }
</style>
</head>
<body>

<div class="update-container">
    <h2><i class="fas fa-user-edit"></i> Edit Your Account Details</h2>
    
    <?php if ($error_message): ?>
        <div class="error"><?= $error_message ?></div>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <div class="success"><?= $success_message ?></div>
    <?php endif; ?>
    
    <p style="text-align:center; color:#555;">Updating details for: **<?= htmlspecialchars($_SESSION['user_to_update_email']) ?>**</p>
    
    <form action="account_settings.php" method="post">
        <input type="hidden" name="action" value="update_profile"> 
        
        <div class="input-group">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= $current_username ?>" placeholder="New Username" required>
        </div>
        
        <div class="input-group">
            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="<?= $current_phone ?>" placeholder="New Phone Number" required>
        </div>
        
        <div class="input-group">
            <label for="country_code">Country Code:</label>
            <input type="text" name="country_code" id="country_code" value="<?= $current_country_code ?>" placeholder="New Country Code">
        </div>
        
        <div class="input-group">
            <label for="new_password">New Password (optional):</label>
            <input type="password" name="new_password" id="new_password" placeholder="Leave blank to keep current password">
        </div>
        
        <button type="submit" class="update-btn">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </form>
    
    <p><a href="login.html">Cancel and return to Login</a></p>
</div>

</body>
</html>
<?php 
} else {
    mysqli_close($conn);
    if (!isset($_POST['email'])) {
        header("Location: login.html?error=" . urlencode("Please verify your identity via the login page first."));
        exit();
    }
}
?>