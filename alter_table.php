<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

try {
    $db = get_db_connection();
    $db->exec("ALTER TABLE returns ADD COLUMN photo_path VARCHAR(255) NULL AFTER description;");
    echo "Success: Column photo_path added.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column photo_path already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
