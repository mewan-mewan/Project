<?php
include("includes/auth.php");
checkLogin();
include("includes/db.php");

$message = '';
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'clear_orders'){
 
  if(mysqli_query($conn, "TRUNCATE TABLE orders")){
    $message = 'All orders have been cleared.';
  } else {
    $message = 'DB error: ' . mysqli_error($conn);
  }
}

$result = mysqli_query($conn, "SELECT orders.id, products.name AS product_name, customer_name, customer_email, order_date 
                 FROM orders 
                 JOIN products ON orders.product_id = products.id 
                 ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Orders</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <h1>Customer Orders</h1>
</header>

<div class="dashboard">
  <?php if($message): ?><p class="note" style="color:green"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

  <form method="POST" onsubmit="return confirm('Are you sure you want to clear all orders? This cannot be undone.');" style="margin-bottom:12px;">
    <input type="hidden" name="action" value="clear_orders">
    <button type="submit" class="admin-btn">Clear Orders</button>
  </form>

  <?php if(mysqli_num_rows($result) === 0): ?>
    <div class="orders-empty">No orders found.</div>
  <?php else: ?>
    <table class="orders-table">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Product</th>
          <th>Customer Name</th>
          <th>Email</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
      <?php while($row = mysqli_fetch_assoc($result)){ ?>
      <tr>
        <td><?php echo htmlspecialchars($row['id']); ?></td>
        <td><?php echo htmlspecialchars($row['product_name']); ?></td>
        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
        <td><?php echo htmlspecialchars($row['customer_email']); ?></td>
        <td><?php echo htmlspecialchars($row['order_date']); ?></td>
      </tr>
      <?php } ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
