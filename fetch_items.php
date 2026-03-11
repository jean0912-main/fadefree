<?php
include 'db_connect.php';

$result = mysqli_query($conn, "SELECT * FROM products");
$items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

echo json_encode($items); // Sends data to JS in a clean format
?>