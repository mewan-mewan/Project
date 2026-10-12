<?php
session_start();
include("db.php");

function login($username, $password){
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT username, password FROM admins WHERE username = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if($admin && password_verify($password, $admin['password'])){
        session_regenerate_id(true);
        $_SESSION['admin'] = $admin['username'];
        return true;
    }
    return false;
}

function checkLogin(){
    if(!isset($_SESSION['admin'])){
        header("Location: login.php");
        exit();
    }
}
?>
