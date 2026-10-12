<?php
// Usage: php reset_admin_cli.php username password
if(PHP_SAPI !== 'cli'){
    echo "This script must be run from the command line.\n";
    exit(1);
}

if($argc < 3){
    echo "Usage: php reset_admin_cli.php username password\n";
    exit(1);
}

$username = $argv[1];
$password = $argv[2];

require_once __DIR__ . '/../includes/db.php';


if(!isset($conn) || !$conn){
    echo "Database connection not available.\n";
    exit(1);
}

$check = mysqli_query($conn, "SHOW TABLES LIKE 'admins'");
if(!$check || mysqli_num_rows($check) == 0){
    echo "Table `admins` does not exist in database.\n";
    exit(1);
}


$u = mysqli_real_escape_string($conn, $username);
$h = mysqli_real_escape_string($conn, password_hash($password, PASSWORD_DEFAULT));


mysqli_begin_transaction($conn);
try{
    mysqli_query($conn, "TRUNCATE TABLE admins");
    $res = mysqli_query($conn, "INSERT INTO admins (username, password) VALUES ('$u', '$h')");
    if(!$res) throw new Exception(mysqli_error($conn));
    mysqli_commit($conn);
    echo "Admin reset successful. New username: $username\n";
    exit(0);
} catch(Exception $e){
    mysqli_rollback($conn);
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

?>
