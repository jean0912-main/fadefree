<?php
include 'db_connect.php';
$id = $_POST['id'];
$query = "DELETE FROM products WHERE id = $id";
if (mysqli_query($conn, $query)) echo "Success";
?>