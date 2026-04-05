<?php
include_once '../config/database.php';
require_once '../helpers/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $source = trim($_POST['source']);
    $amount = floatval($_POST['amount']);
    $date = trim($_POST['date']);

    if($amount <= 0)
    {
        $_SESSION['messages'][] = 'Amount must be greater than zero.';
        header("Location: list.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO income (user_id, source, amount, date) 
                           VALUES (?,?,?,?)");
    $stmt->bind_param("isds", $_SESSION['user_id'], $source, $amount, $date);

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