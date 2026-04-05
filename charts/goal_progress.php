<?php
session_start();
include_once '../config/database.php';
require_once '../helpers/auth.php';
require_login();

$query = "SELECT goal_name, target_amount, saved_amount FROM savings_goals WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$data = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Savings Goal Progress</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="../assets/css/style.css"/>
</head>
<body class="goal-progress-body">

<?php include '../includes/header.php'; ?>

<div class="goal-progress-layout">
    <table class="goal-progress-table">
        <thead>
            <tr>
                <th>Goal</th>
                <th>Target Amount</th>
                <th>Saved</th>
                <th>Progress</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($data)): 
            $target = $row['target_amount'];
            $saved = $row['saved_amount'];
            $percent = $target > 0 ? ($saved / $target) * 100 : 0;
            $percent = min($percent, 100); 
        ?>
            <tr>
                <td><?= htmlspecialchars($row['goal_name']) ?></td>
                <td>₱<?= number_format($target, 2); ?></td>
                <td>₱<?= number_format($saved, 2); ?></td>
                <td>
                    <div class="progress-wrapper">
                        <div class="progress-container">
                            <div class="progress-bar" style="width: <?= $percent ?>%;"></div>
                        </div>
                        <small><?= number_format($percent, 1) ?>%</small>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
