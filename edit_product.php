<?php
include("includes/auth.php");
checkLogin();
include("includes/db.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id <= 0){
    echo "Invalid product id.";
    exit;
}
$stmt = mysqli_prepare($conn, "SELECT id, name, description, category, price, image FROM products WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    if(!empty($_FILES['image']['name'])){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid('img_', true) . '.' . $ext;
        $target = __DIR__ . '/uploads/' . $image;
        if(!move_uploaded_file($_FILES['image']['tmp_name'], $target)){
            echo "Failed to upload image.";
            exit;
        }
        $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, description=?, category=?, price=?, image=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'sssdsi', $name, $desc, $category, $price, $image, $id);
        if(mysqli_stmt_execute($stmt)){
            echo "Product updated successfully!";
        } else {
            echo "DB error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE products SET name=?, description=?, category=?, price=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, 'sssdi', $name, $desc, $category, $price, $id);
        if(mysqli_stmt_execute($stmt)){
            echo "Product updated successfully!";
        } else {
            echo "DB error: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<h2>Edit Product</h2>
<form method="POST" enctype="multipart/form-data">
  <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
  <textarea name="description"><?php echo $product['description']; ?></textarea><br>
  <input type="text" name="category" value="<?php echo $product['category']; ?>"><br>
  <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>"><br>
  <img src="uploads/<?php echo $product['image']; ?>" width="100"><br>
  <input type="file" name="image"><br>
  <button type="submit" name="submit">Update Product</button>
</form>
