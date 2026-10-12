<?php
include("includes/auth.php");
checkLogin();
include("includes/db.php");

if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    if($id <= 0){
        echo "Invalid id.";
        exit;
    }


    $stmt = mysqli_prepare($conn, "SELECT image FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    if(mysqli_stmt_execute($stmt)){
        if(!empty($row['image'])){
            $path = __DIR__ . '/uploads/' . $row['image'];
            if(file_exists($path)) @unlink($path);
        }
        echo "Product Deleted Successfully!";
    } else {
        echo "DB error: " . mysqli_error($conn);
    }
    echo "<br><a href='view_products.php'>Back to Products</a>";
}
?>
