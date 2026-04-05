<?php
$host = 'localhost';
$username = 'root';
$password = '';
$db = 'budget_db';
$port = '3306';

// Connect to MySQL server without specifying database
$conn = mysqli_connect($host, $username, $password, '', $port);
if (!$conn) 
{
	die('Connection failed: ' . mysqli_connect_error());
}

// Create database if it does not exist
$sql = "CREATE DATABASE IF NOT EXISTS $db";
if (!mysqli_query($conn, $sql)) 
{
	die('Connection failed: ' . mysqli_connect_error());
}

// Connect to the newly created database
mysqli_select_db($conn, $db);

// Create table if it does not exist
$usersCreate = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);";
$incomeCreate = "CREATE TABLE IF NOT EXISTS income (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    source VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);";
$expensesCreate = "CREATE TABLE IF NOT EXISTS expenses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    amount DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);";
$savingsGoalsCreate = "CREATE TABLE IF NOT EXISTS Savings_Goals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    goal_name VARCHAR(100) NOT NULL,
    target_amount DECIMAL(10, 2) NOT NULL,
    saved_amount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    start_date DATE NOT NULL,
    end_date DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);";

if (!mysqli_query($conn, $usersCreate) || 
    !mysqli_query($conn, $incomeCreate) || 
    !mysqli_query($conn, $expensesCreate) || 
    !mysqli_query($conn, $savingsGoalsCreate)) 
{
    die('Connection failed: ' . mysqli_connect_error());
}
?>