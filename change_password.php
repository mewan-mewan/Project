<?php
include("includes/auth.php");
checkLogin();
include("includes/db.php");

$error = '';
$success = '';

// current admin username from session
$admin = isset($_SESSION['admin']) ? $_SESSION['admin'] : '';
if(!$admin){
    header('Location: login.php');
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if($current === '' || $new === '' || $confirm === ''){
        $error = 'All fields are required.';
    } elseif($new !== $confirm){
        $error = 'New password and confirmation do not match.';
    } elseif(strlen($new) < 6){
        $error = 'New password must be at least 6 characters.';
    } else {
        // fetch stored hash for current admin
        $stmt = mysqli_prepare($conn, "SELECT password FROM admins WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $admin);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($res);
        mysqli_stmt_close($stmt);

        if(!$row || !password_verify($current, $row['password'])){
            $error = 'Curretnt passowors is incorrect.';
        } else {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "UPDATE admins SET password = ? WHERE username = ?");
            mysqli_stmt_bind_param($stmt, 'ss', $hash, $admin);
            if(mysqli_stmt_execute($stmt)){
                $success = 'Password updated successfully.';
            } else {
                $error = 'DB error: ' . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Change Password</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <h1>Change Password</h1>
</header>

<div class="dashboard">
  <div class="login-box">
    <?php if($error): ?><p class="note" style="color:red"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
    <?php if($success): ?><p class="note" style="color:green"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>

    <form method="POST">
      <input type="password" name="current_password" placeholder="Current password" required><br>
      <input type="password" name="new_password" placeholder="New password" required><br>
      <input type="password" name="confirm_password" placeholder="Confirm new password" required><br>
      <div style="display:flex; gap:8px; align-items:center;">
        <button type="submit" class="btn">Update Password</button>
        <a href="dashboard.php" class="btn">Back</a>
      </div>
    </form>
  </div>
</div>

</body>
</html>
