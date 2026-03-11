<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $query = "INSERT INTO products (name, price, stock) VALUES ('$name', '$price', '$stock')";
    
    if (mysqli_query($conn, $query)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>