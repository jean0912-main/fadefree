-- Run this SQL in your database to create the receipts table

CREATE TABLE IF NOT EXISTS receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_num VARCHAR(50) UNIQUE NOT NULL,
    items_list TEXT NOT NULL,
    total VARCHAR(20) NOT NULL,
    item_count INT NOT NULL,
    date_time DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Also add units_sold column to products table if it doesn't exist
ALTER TABLE products ADD COLUMN IF NOT EXISTS units_sold INT DEFAULT 0;
