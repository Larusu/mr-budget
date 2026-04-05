<?php
// Start a new or resume existing session (to store user data between pages)
session_start();
require_once '../config/database.php';

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Store email in session to retain it on failed attempts
    $_SESSION['old_email'] = $email;

    if (empty($email) || empty($password)) 
    {
        $_SESSION['messages'][] = "Email and password are required.";
        header("Location: ../index.php");
        exit();
    }

    // Prepare the SQL query to find the user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);   // Bind the email parameter to the query

    // Try to run the query
    if (!$stmt->execute())
    {
        $_SESSION['messages'][] = "Database error: " . $stmt->error;
        header("Location: ../index.php");
        exit();
    }

    // Get the result of the executed query
    $result = $stmt->get_result();

    // Check if the user was found with that email
    if ($result->num_rows === 0) 
    {
        $_SESSION['messages'][] = "No user found with that email.";
        header("Location: ../index.php");
        exit();
    }

    // If we reach here, 1 user was found
    $user = $result->fetch_assoc();     // Get user data as an associative array

    // Check if the password entered matches the hashed password in the database
    if (password_verify($password, $user['password'])) 
    {
        // Password is correct, store user ID in session to keep them logged in
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username']; // Make sure your table has a 'username' column

        // Cleanup: remove preserved email on successful login
        unset($_SESSION['old_email']);

        header("Location: ../dashboard/index.php");
        exit();
    } 
    else 
    {
        $_SESSION['messages'][] = "Invalid password.";
        header("Location: ../index.php");
        exit();
    }
}
?>
