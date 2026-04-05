<?php
include_once '../config/database.php';
require_once '../helpers/auth.php';
require_login();

$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);

if (!$stmt->execute()) 
{
    $_SESSION['messages'][] = "Database error: " . $stmt->error;
    header("Location: ../index.php");
    exit();
}

$data = $stmt->get_result();
$row = $data->fetch_assoc();

$username = $row['username'];
$email = $row['email'];

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $newUsername = trim($_POST['username']);
    $newEmail = trim($_POST['email']);
    $newPassword = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if($confirmPassword !== $newPassword)
    {
        $_SESSION['messages'][] = "Password do not match!";
        header('Location: profile.php');
        exit();
    }

    $hashedPassword = !empty($newPassword) 
        ? password_hash($newPassword, PASSWORD_DEFAULT)
        : $row['password'];

    $updateQuery = "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssi", $newUsername, $newEmail, $hashedPassword, $_SESSION['user_id']);

    if($stmt->execute())
    {
        $_SESSION['messages'][] = "Updated successfully!";
        header('Location: profile.php');
        exit();
    }
    else
    {
        $_SESSION['messages'][] = "Update failed: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="profile-body">
    
    <?php include '../includes/header.php'; ?>
    
    <div class="profile-container">
        <div class="profile-label">
            <i class="fa-solid fa-address-card"></i>
            <h1>Change Profile</h1>
        </div>
        
        <div class="form-container">
            <form method="POST">
                <label for="username">Username:</label><br>
                <input name="username" placeholder="<?=$username?>" value="<?=$username?>"><br>

                <label for="email">Email:</label><br>
                <input name="email" placeholder="<?=$email?>" value="<?=$email?>"><br>

                <label for="password">New Password:</label><br>
                <input type="password" name="password"><br>

                <label for="confirmPassword">Confirm Password:</label><br>
                <input type="password" name="confirmPassword"><br>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>  
</body>
</html>