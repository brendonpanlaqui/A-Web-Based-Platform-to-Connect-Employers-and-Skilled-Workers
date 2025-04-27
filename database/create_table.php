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

// SQL to create the jobs table with nullable location and salary
$sql = "CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    type ENUM('Online', 'Offline') NOT NULL,
    platform VARCHAR(255) NULL,
    location VARCHAR(255) NULL,
    time_estimate VARCHAR(255) NOT NULL,
    expertise_level ENUM('Entry', 'Immediate', 'Expert') NOT NULL,
    salary DECIMAL(10, 2) NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employer_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute the SQL
if (mysqli_query($con, $sql)) {
    echo "✅ Table 'jobs' created successfully.";
} else {
    echo "❌ Error creating table: " . mysqli_error($con);
}

// Close the connection
mysqli_close($con);
?>
