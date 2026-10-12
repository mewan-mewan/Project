<?php
include("includes/db.php");
include("includes/auth.php");

$error = '';


if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        if(login($username, $password)){
                header('Location: dashboard.php');
                exit;
        } else {
                $error = 'Invalid credentials.';
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <title>Login</title>
        <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="login-wrapper">
        <div class="login-box">
                <?php if($error): ?><p class="note" style="color:red"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>

                        <h2>Admin Login</h2>
                        <form method="POST">
                                <input type="text" name="username" placeholder="Username" required><br>
                                <input type="password" name="password" placeholder="Password" required><br>
                                <button type="submit" class="btn">Login</button>
                        </form>

        </div>
</div>

</body>
</html>
