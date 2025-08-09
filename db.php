<?php
$host = "localhost";
$username = "uac1gp3zeje8t";
$password = "hk8ilpc7us2e";
$database = "dbsb5zz97lw9e7";

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
