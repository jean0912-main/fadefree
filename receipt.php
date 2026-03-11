<?php
include 'db_connect.php';

// Get receipt ID from URL parameter
$receiptNum = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (empty($receiptNum)) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Error - No Receipt ID</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 50px; text-align: center; }
            .error { color: #e74c3c; font-size: 1.2em; }
        </style>
    </head>
    <body>
        <div class="error">❌ Error: No receipt number provided</div>
    </body>
    </html>
    <?php
    exit;
}

// Fetch receipt from database
$sql = "SELECT * FROM receipts WHERE receipt_num = '$receiptNum'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Database Error</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 50px; text-align: center; }
            .error { color: #e74c3c; }
        </style>
    </head>
    <body>
        <div class="error">Database error: <?php echo mysqli_error($conn); ?></div>
    </body>
    </html>
    <?php
    exit;
}

if (mysqli_num_rows($result) == 0) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Receipt Not Found</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 50px; text-align: center; }
            .error { color: #e74c3c; font-size: 1.2em; }
            .info { color: #666; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="error">❌ Receipt Not Found</div>
        <div class="info">Receipt ID: <?php echo htmlspecialchars($receiptNum); ?> does not exist in the database.</div>
    </body>
    </html>
    <?php
    exit;
}

$receipt = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FadeFree Receipt - <?php echo $receipt['receipt_num']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 20px;
        }
        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: 2px dashed #333;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #333;
        }
        .receipt-header h1 {
            font-size: 1.8em;
            margin: 10px 0;
        }
        .receipt-header p {
            color: #666;
            font-size: 0.9em;
            margin: 5px 0;
        }
        .receipt-meta {
            text-align: center;
            font-size: 0.85em;
            color: #666;
            margin: 15px 0;
            padding: 10px 0;
            border-top: 1px dashed #333;
            border-bottom: 1px dashed #333;
        }
        .receipt-items {
            margin: 20px 0;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dotted #ccc;
            font-size: 0.95em;
        }
        .item-row .item-name {
            flex: 1;
        }
        .item-row .item-price {
            text-align: right;
            width: 80px;
        }
        .receipt-total {
            margin: 20px 0;
            padding: 15px 0;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
            text-align: right;
            font-weight: bold;
            font-size: 1.2em;
        }
        .receipt-footer {
            text-align: center;
            font-size: 0.85em;
            color: #666;
            margin: 20px 0;
        }
        .receipt-footer p {
            margin: 5px 0;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: center;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95em;
            font-weight: bold;
        }
        .btn-print {
            background: #3498db;
            color: white;
        }
        .btn-print:hover {
            background: #2980b9;
        }
        .btn-back {
            background: #95a5a6;
            color: white;
        }
        .btn-back:hover {
            background: #7f8c8d;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .action-buttons {
                display: none;
            }
            .receipt-container {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="receipt-header">
        <h1>🛍️ FadeFree</h1>
        <p>Digital POS System</p>
        <p>Receipt #<?php echo htmlspecialchars($receipt['receipt_num']); ?></p>
    </div>

    <div class="receipt-meta">
        <p><?php echo date('m/d/Y H:i:s', strtotime($receipt['date_time'])); ?></p>
    </div>

    <div class="receipt-items">
        <div class="item-row" style="border-bottom: 1px solid #333; font-weight: bold;">
            <div class="item-name">Item</div>
            <div class="item-price">Price</div>
        </div>
        <?php
        // Parse items from the items_list (format: "Item1 (₱100.00), Item2 (₱150.00)")
        $itemsList = $receipt['items_list'];
        
        // Split by ), but keep the ) with the number
        $items = array_filter(array_map('trim', explode('), ', $itemsList . ')')));
        
        foreach ($items as $item) {
            $item = trim($item);
            if (empty($item) || $item === ')') continue;
            
            // Remove trailing ) if exists
            $item = rtrim($item, ')');
            
            // Parse item name and price: "Item Name (₱100.00"
            preg_match('/(.+?)\s*\(₱([\d.]+)/', $item, $matches);
            if (count($matches) >= 3) {
                $name = trim($matches[1]);
                $price = trim($matches[2]);
                echo '<div class="item-row">';
                echo '<div class="item-name">' . htmlspecialchars($name) . '</div>';
                echo '<div class="item-price">₱' . number_format((float)$price, 2) . '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>

    <div class="receipt-total">
        TOTAL: ₱<?php echo number_format((float)str_replace('₱', '', $receipt['total']), 2); ?>
    </div>

    <div class="receipt-footer">
        <p><strong>Thank you for your purchase!</strong></p>
        <p>Items Sold: <?php echo $receipt['item_count']; ?></p>
        <p style="margin-top: 10px; font-style: italic;">Your FadeFree Receipt</p>
    </div>

    <div class="action-buttons">
        <button class="btn-print" onclick="window.print()">🖨️ Print</button>
        <button class="btn-back" onclick="window.history.back()">← Back</button>
    </div>
</div>

</body>
</html>
