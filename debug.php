<?php
include 'db_connect.php';

// Check if receipts table exists
$checkTable = mysqli_query($conn, "SHOW TABLES LIKE 'receipts'");
$tableExists = mysqli_num_rows($checkTable) > 0;

// Get all receipts for testing
$receipts = array();
if ($tableExists) {
    $result = mysqli_query($conn, "SELECT * FROM receipts ORDER BY date_time DESC LIMIT 10");
    while ($row = mysqli_fetch_assoc($result)) {
        $receipts[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FadeFree - Debug</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        h1 { color: #333; }
        .status { padding: 15px; border-radius: 4px; margin: 10px 0; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h1>🐛 FadeFree Debug Panel</h1>
    
    <div class="status <?php echo $tableExists ? 'success' : 'error'; ?>">
        <strong>Receipts Table:</strong> 
        <?php echo $tableExists ? '✅ Exists' : '❌ Missing - Run setup_receipts_table.sql'; ?>
    </div>

    <?php if (!$tableExists): ?>
    <div class="error" style="padding: 10px; margin: 10px 0;">
        Run this SQL in phpMyAdmin:<br><br>
        <code style="background: #f0f0f0; padding: 10px; display: block;">
CREATE TABLE receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_num VARCHAR(50) UNIQUE NOT NULL,
    items_list TEXT NOT NULL,
    total VARCHAR(20) NOT NULL,
    item_count INT NOT NULL,
    date_time DATETIME DEFAULT CURRENT_TIMESTAMP
);
        </code>
    </div>
    <?php else: ?>
    
    <h2>Recent Receipts (Last 10)</h2>
    <?php if (count($receipts) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Receipt #</th>
                    <th>Total</th>
                    <th>Items</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($receipts as $r): ?>
                <tr>
                    <td><?php echo htmlspecialchars($r['receipt_num']); ?></td>
                    <td><?php echo htmlspecialchars($r['total']); ?></td>
                    <td><?php echo $r['item_count']; ?></td>
                    <td><?php echo htmlspecialchars($r['date_time']); ?></td>
                    <td><a href="receipt.php?id=<?php echo urlencode($r['receipt_num']); ?>" target="_blank">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="error">No receipts yet. Generate one from the POS system first.</div>
    <?php endif; ?>
    
    <?php endif; ?>

    <div style="margin-top: 20px; padding: 15px; background: #e7f3ff; border-radius: 4px; border-left: 4px solid #2196F3;">
        <strong>✓ Server URL:</strong> 
        <?php 
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $path = dirname($_SERVER['PHP_SELF']);
            echo "$protocol://$host$path/";
        ?>
    </div>
</div>

</body>
</html>
