<?php
include 'db_connect.php';

$id = $_POST['id'];

// Decrease stock by 1 and increase units_sold by 1
$query = "UPDATE products SET stock = stock - 1, units_sold = units_sold + 1 WHERE id = $id AND stock > 0";

if (mysqli_query($conn, $query)) {
    echo "Success";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>