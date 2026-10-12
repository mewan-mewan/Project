<?php
include("includes/db.php");

if(!isset($_REQUEST['id'])){
    echo "Product not specified.";
    exit;
}

$id = intval($_REQUEST['id']);
if($id <= 0){
    echo "Invalid product id.";
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT id, name, price, image, description FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if(!$product){
    echo "Product not found.";
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = trim($_POST['customer_name'] ?? '');
    $email = trim($_POST['customer_email'] ?? '');
    $product_id = intval($_POST['product_id'] ?? 0);

    if($product_id !== $id){
        $message = 'Product mismatch.';
    } elseif($name === '' || $email === ''){
        $message = 'Name and email are required.';
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = 'Please enter a valid email address.';
    } else {
        $stmt = mysqli_prepare($conn, "INSERT INTO orders (product_id, customer_name, customer_email, order_date) VALUES (?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, 'iss', $product_id, $name, $email);
        if(mysqli_stmt_execute($stmt)){
            $message = 'Purchase successful! Thank you.';
        } else {
            $message = 'DB error: ' . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Purchase - <?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <h1>Purchase</h1>
</header>

<div class="purchase-page">
    <?php if(isset($message)): ?><div class="purchase-success"><?php echo htmlspecialchars($message); ?></div><?php endif; ?>

    <div class="product-detail">
        <?php if(!empty($product['image']) && file_exists(__DIR__ . '/uploads/' . $product['image'])): ?>
            <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        <?php else: ?>
            <div style="width:320px;max-width:40%;height:180px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#999;">No image</div>
        <?php endif; ?>
        <div class="product-info">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <?php if(isset($product['price'])): ?><div class="product-price">Rs. <?php echo htmlspecialchars($product['price']); ?></div><?php endif; ?>
            <?php if(!empty($product['description'])): ?><p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p><?php endif; ?>

            <div class="purchase-form">
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="text" name="customer_name" placeholder="Your Name" required><br>
                    <input type="email" name="customer_email" placeholder="Your Email" required><br>
                    <button type="submit" class="btn">Confirm Purchase</button>
                    <a href="view_products.php" class="btn">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
