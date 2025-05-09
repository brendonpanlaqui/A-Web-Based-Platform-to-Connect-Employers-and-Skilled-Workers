<?php
// database/create_table.php

require_once __DIR__ . '/../config/database.php';

// Users
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

// Jobs
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
    status ENUM('open', 'ongoing', 'completed', 'cancelled') DEFAULT 'open', 
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

// Applications
$sql = "CREATE TABLE IF NOT EXISTS job_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    worker_id INT NOT NULL,
    cover_letter TEXT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    date_applied TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_worker_job (worker_id, job_id),
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (worker_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($con, $sql)) {
    echo "✅ Table 'job_applications' created successfully.<br>";
} else {
    echo "❌ Error creating job_applications table: " . mysqli_error($con) . "<br>";
}

// Reports Table
$sql = "CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    reported_type ENUM('user', 'job') NOT NULL,
    reported_id INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('Pending', 'Reviewed', 'Resolved') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if (mysqli_query($con, $sql)) {
    echo "✅ Table 'reports' created successfully.<br>";
} else {
    echo "❌ Error creating reports table: " . mysqli_error($con) . "<br>";
}

// Close the connection
mysqli_close($con);
?>
