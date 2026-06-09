<?php
/**
 * Setup script for the users.rust_counters column.
 * Run this script once to add the column if needed and backfill existing rows.
 */

include_once 'includes/dbh.inc.php';

$conn = GetDBConnection(DBL_SETUP_RUST_COUNTERS_COLUMN);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

try {
    $columnExists = false;
    $columnCheckSql = "SELECT COUNT(*) AS columnCount
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'users'
          AND COLUMN_NAME = 'rust_counters'";
    $columnCheckResult = $conn->query($columnCheckSql);
    if ($columnCheckResult !== false) {
        $columnCheckRow = $columnCheckResult->fetch_assoc();
        $columnExists = ((int)$columnCheckRow['columnCount']) > 0;
        $columnCheckResult->free();
    }

    if (!$columnExists) {
        $alterSql = "ALTER TABLE `users` ADD COLUMN `rust_counters` INT(11) NOT NULL DEFAULT 0";
        $conn->query($alterSql);
    }

    $updateSql = "UPDATE `users` SET `rust_counters` = 0 WHERE `rust_counters` IS NULL";
    $conn->query($updateSql);
    $updatedRows = $conn->affected_rows;

    echo "rust_counters column ready.";
    if (!$columnExists) {
        echo " Column was added.";
    } else {
        echo " Column already existed.";
    }
    echo " Backfilled rows: " . $updatedRows . ".";
    http_response_code(200);
} catch (\Throwable $e) {
    echo "Error setting up rust_counters column: " . $e->getMessage();
    http_response_code(500);
} finally {
    $conn->close();
}
?>
