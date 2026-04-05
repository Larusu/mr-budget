<?php
include_once '../config/database.php';
require_once '../helpers/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $amount = floatval($_POST['amount']);
    $date = trim($_POST['date']);

    if($amount <= 0)
    {
        $_SESSION['messages'][] = 'Amount must be greater than zero.';
        header("Location: list.php");
        exit();
    }

    $query = "INSERT INTO expenses (user_id, category, description, amount, date)
              VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("issds", $_SESSION['user_id'], $category, $description, $amount, $date);

    if($stmt->execute())
    {
        $_SESSION['messages'][] = 'Added to income!';
    }
    else
    {
        $_SESSION['messages'][] = 'Adding goal Failed: ' . $stmt->error;
    }

    header("Location: list.php");
    exit();
}
?>