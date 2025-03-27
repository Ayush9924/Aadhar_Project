CREATE DATABASE IF NOT EXISTS exam_auth_system;
USE exam_auth_system;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'supervisor', 'student') NOT NULL DEFAULT 'student',
    aadhaar_number VARCHAR(12) UNIQUE DEFAULT NULL,
    aadhaar_verified TINYINT(1) NOT NULL DEFAULT 0,
    exam_center_id INT DEFAULT NULL, -- Links user to an exam center
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (email)
);

CREATE TABLE IF NOT EXISTS exam_centers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE users 
ADD CONSTRAINT fk_exam_center FOREIGN KEY (exam_center_id) REFERENCES exam_centers(id) ON DELETE SET NULL;
