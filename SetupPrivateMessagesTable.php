<?php
/**
 * Setup script for private messaging database table
 * Run this script once to create the private_messages table
 */

include_once 'includes/dbh.inc.php';

$conn = GetDBConnection();

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "CREATE TABLE IF NOT EXISTS `private_messages` (
  `messageId` INT AUTO_INCREMENT PRIMARY KEY,
  `fromUserId` INT NOT NULL,
  `toUserId` INT NOT NULL,
  `message` LONGTEXT,
  `gameLink` VARCHAR(500),
  `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `isRead` BOOLEAN DEFAULT FALSE,
  INDEX `idx_from_to` (`fromUserId`, `toUserId`),
  INDEX `idx_to_unread` (`toUserId`, `isRead`),
  INDEX `idx_created` (`createdAt`),
  FOREIGN KEY (`fromUserId`) REFERENCES `users`(`usersId`) ON DELETE CASCADE,
  FOREIGN KEY (`toUserId`) REFERENCES `users`(`usersId`) ON DELETE CASCADE
)";

if ($conn->query($sql) === TRUE) {
    echo "private_messages table created successfully or already exists.";
    http_response_code(200);
} else {
    echo "Error creating table: " . $conn->error;
    http_response_code(500);
}

$conn->close();
?>
