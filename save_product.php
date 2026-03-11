<?php
include 'db_connect.php';

$name = $_POST['name'];
$price = $_POST['price'];
$stock = $_POST['stock'];

$sql = "INSERT INTO products (name, price, stock) VALUES ('$name', '$price', '$stock')";

if (mysqli_query($conn, $sql)) {
    echo "Product saved successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>