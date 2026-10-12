<?php
include("includes/db.php");
include("includes/auth.php"); // to check if admin session exists

$stmt = mysqli_prepare($conn, "SELECT id, name, price, image FROM products ORDER BY id DESC");
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

$isAdmin = isset($_SESSION['admin']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Products</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <h1><?php echo $isAdmin ? 'Manage Products' : 'Available Products'; ?></h1>
</header>

<div class="dashboard">
  <?php if($isAdmin): ?><a href="add_product.php" class="btn">Add New Product</a><?php endif; ?>

  <div class="products-grid">
    <?php while($row = mysqli_fetch_assoc($res)){ ?>
      <div class="card product-card">
        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
        <div class="card-body">
          <div class="card-title"><?php echo htmlspecialchars($row['name']); ?></div>
          <div class="card-text">Rs. <?php echo htmlspecialchars($row['price']); ?></div>
          <div class="product-actions">
            <?php if($isAdmin): ?>
              <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn">Edit</a>
              <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="admin-btn">Delete</a>
            <?php else: ?>
              <a href="purchase.php?id=<?php echo $row['id']; ?>" class="btn">Buy Now</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>
</body>
</html>
