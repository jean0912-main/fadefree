<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $query = "DELETE FROM products WHERE id = $id";
    if (mysqli_query($conn, $query)) echo "Success";
} else {
    echo "Error: Invalid request method";
}
?>