<?php
// database/create_table.php

// Include the database connection
require_once __DIR__ . '/../config/database.php';

// SQL to create the users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'employer', 'worker') DEFAULT 'worker',
    specialties VARCHAR(255) NULL,
    profile_photo VARCHAR(255) NULL,
    bio TEXT NULL,
    contact_number VARCHAR(20) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute the SQL
if (mysqli_query($con, $sql)) {
    echo "✅ Table 'users' created successfully.";
} else {
    echo "❌ Error creating table: " . mysqli_error($con);
}

// Close the connection
mysqli_close($con);
?>
