<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

$receiptNum = $_POST['receiptNum'];
$cartData = $_POST['cartData']; // JSON string
$total = $_POST['total'];
$itemCount = $_POST['itemCount'];

// Decode the cart data
$cartArray = json_decode($cartData, true);
$itemsList = '';
foreach ($cartArray as $item) {
    $itemsList .= $item['name'] . ' (₱' . $item['price'] . '), ';
}
$itemsList = rtrim($itemsList, ', ');

$dateTime = date('Y-m-d H:i:s');

// Insert receipt into database
$sql = "INSERT INTO receipts (receipt_num, items_list, total, item_count, date_time) 
        VALUES ('$receiptNum', '$itemsList', '$total', '$itemCount', '$dateTime')";

if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true, 'receiptNum' => $receiptNum]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
}
?>
