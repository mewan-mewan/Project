<?php
include("includes/db.php");
$result = mysqli_query($conn, "SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="stylesheet" href="css/style.css">
  <meta charset="UTF-8">
  <title>NSBM Shop</title>
</head>
<body>
<header>
  <h1>Welcome to NSBM Shop</h1>
  <p>Exclusive products for NSBM students and staff</p>
</header>

<div class="slideshow-container">
  <div class="slide active slide-1">
    <div class="slide-inner">
      <h2>Fresh arrivals</h2>
      <p>Discover new items added daily — perfect for NSBM students and staff.</p>
    </div>
  </div>
  <div class="slide slide-2">
    <div class="slide-inner">
      <h2>Best deals</h2>
      <p>Save more on your favourite product with student-friendly pricing.</p>
    </div>
  </div>
  <div class="slide slide-3">
    <div class="slide-inner">
      <h2>Fast checkout</h2>
      <p>Buy now in just a few clicks and get your order confirmed immediately.</p>
    </div>
  </div>
</div>
<div class="slide-dots">
  <span class="dot active"></span>
  <span class="dot"></span>
  <span class="dot"></span>
</div>

<div class="page-content" style="padding:20px;">
  <h2>Available products</h2>


  <input type="text" id="searchBar" class="search-bar" placeholder="Search products...">

  <div class="products-grid">
    <?php while($row = mysqli_fetch_assoc($result)){ ?>
      <div class="card">
        <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
        <h5 class="card-title"><?php echo $row['name']; ?></h5>
        <p class="card-text">Rs. <?php echo $row['price']; ?></p>
        <a href="purchase.php?id=<?php echo $row['id']; ?>"
           class="btn buy-btn"
           data-product="<?php echo $row['name']; ?>">Buy Now</a>
      </div>
    <?php } ?>
  </div>
</div>


<div style="text-align:center; margin:20px;">
  <a href="login.php" class="btn">Go to Admin Panel</a>
</div>



<script src="js/script.js"></script>
</body>
</html>
