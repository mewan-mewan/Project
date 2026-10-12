<?php
include("includes/auth.php");
checkLogin();
include("includes/db.php");

if(isset($_POST['submit'])){
  $name = trim($_POST['name']);
  $desc = trim($_POST['description']);
  $category = trim($_POST['category']);
  $price = floatval($_POST['price']);

  $image = null;
  if(!empty($_FILES['image']['name'])){
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image = uniqid('img_', true) . '.' . $ext;
    $target = __DIR__ . '/uploads/' . $image;
    if(!move_uploaded_file($_FILES['image']['tmp_name'], $target)){
      echo "Failed to upload image.";
      exit;
    }
  }

  $stmt = mysqli_prepare($conn, "INSERT INTO products (name, description, category, price, image) VALUES (?, ?, ?, ?, ?)");
  mysqli_stmt_bind_param($stmt, 'sssds', $name, $desc, $category, $price, $image);
  if(mysqli_stmt_execute($stmt)){
    echo "Product added successfully!";
  } else {   
    echo "DB error: " . mysqli_error($conn);
  }
  mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Product</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
  <h1>Add Product</h1>
</header>

<div class="dashboard">
  <div class="login-box">
    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="name" placeholder="Product Name" required><br>
      <textarea name="description" placeholder="Description"></textarea><br>
      <input type="text" name="category" placeholder="Category"><br>
      <input type="number" step="0.01" name="price" placeholder="Price"><br>
      <input type="file" name="image"><br>
      <div style="display:flex; gap:8px; align-items:center;">
        <button type="submit" name="submit" class="btn">Add Product</button>
        <a href="view_products.php" class="btn">Back to Products</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
