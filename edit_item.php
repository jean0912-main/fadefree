<?php
include 'db_connect.php';
$id = $_POST['id'];
$name = mysqli_real_escape_string($conn, $_POST['name']);
$price = $_POST['price'];
$stock = $_POST['stock'];

$query = "UPDATE products SET name='$name', price='$price', stock='$stock' WHERE id=$id";
if (mysqli_query($conn, $query)) echo "Success";
?>