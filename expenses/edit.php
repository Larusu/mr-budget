<?php
include_once '../config/database.php';
require_once '../helpers/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $id = intval($_POST['id'] ?? 0);
    $category = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $date = trim($_POST['date'] ?? '');

    if (!$id || !$source || !$amount || !$date) 
    {
        $_SESSION['messages'][] = 'All fields are required.';
        header("Location: list.php");
        exit();
    }

    if ($amount <= 0) 
    {
        $_SESSION['messages'][] = 'Amount must be greater than zero.';
        header("Location: list.php");
        exit();
    }
    
    $query = "UPDATE expenses SET category = ?, description = ?, amount = ?, date = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdsi", $category, $description, $amount, $date, $id);

    if ($stmt->execute()) 
    {
        $_SESSION['messages'][] = 'Updated successfully!';
        header("Location: list.php");
        exit();
    } 
    else 
    {
        $_SESSION['messages'][] = "Failed to retrieve existing expenses.";
        header("Location: list.php");
        exit();
    }
}
else 
{
   $_SESSION['messages'][] = 'Invalid request method.';
   header("Location: list.php");
    exit();
}
?>
