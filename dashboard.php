<?php
include("includes/auth.php");
checkLogin();
include("includes/db.php");


$productCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];

$orderCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <h1>Admin Dashboard</h1>
</header>

<div class="dashboard">
  <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin']); ?>!</p>

  <div class="stats">
    <div class="stat">
      <div class="number"><?php echo $productCount; ?></div>
      <div>Products</div>
    </div>
    <div class="stat">
      <div class="number"><?php echo $orderCount; ?></div>
      <div>Orders</div>
    </div>
  </div>

  <h3>Quick Links</h3>
  <div class="quick-links">
    <a href="add_product.php" class="btn">Add Product</a>
    <a href="view_products.php" class="btn">Manage Products</a>
    <a href="change_password.php" class="btn">Change Password</a>
    <a href="view_orders.php" class="btn">View Orders</a>
    <a href="logout.php" class="btn">Logout</a>
  </div>
</div>
</body>
</html>
