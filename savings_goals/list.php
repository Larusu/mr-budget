<?php
session_start();
include_once '../config/database.php';
require_once '../helpers/auth.php';
require_login();

$query = "SELECT * FROM savings_goals WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);

if (!$stmt->execute())
{
    $_SESSION['messages'][] = "Failed to retrieve data.";
    header("Location: ../index.php");
    exit();
}

$data = $stmt->get_result();
$totalAmount = 0;
$count = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    if (isset($_POST['delete'])) 
    {
        $stmt = $conn->prepare("DELETE FROM savings_goals WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) 
        {
            $_SESSION['messages'][] = "Deleted successfully!";
            header("Location: list.php");
            exit();
        } else 
        {
            $_SESSION['messages'][] = "Delete failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Savings Goals</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body class="income-body">

<?php include '../includes/header.php'; ?>

<div class="income-layout">
    <table class="income-table">
        <thead>
            <tr>
                <th>Goal</th>
                <th>Target Amount</th>
                <th>Saved</th>
                <th>Start</th>
                <th>End</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
        <?php while ($row = mysqli_fetch_assoc($data)): 
            $totalAmount += $row['saved_amount'];
            $count++;
        ?>
            <tr>
                <td><?= htmlspecialchars($row['goal_name']) ?></td>
                <td>₱<?= number_format($row['target_amount'], 2) ?></td>
                <td>₱<?= number_format($row['saved_amount'], 2) ?></td>
                <td><?= $row['start_date'] ?></td>
                <td><?= $row['end_date'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete"><i class="fas fa-trash"></i></button>
                    </form>

                    <button 
                        type="button" 
                        class="edit-btn" 
                        data-id="<?= $row['id'] ?>"
                        data-goal="<?= htmlspecialchars($row['goal_name']) ?>"
                        data-target="<?= $row['target_amount'] ?>"
                        data-saved="<?= $row['saved_amount'] ?>"
                        data-start="<?= $row['start_date'] ?>"
                        data-end="<?= $row['end_date'] ?>"
                        onclick="openGoalEditModal(this)"
                    >
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Edit Modal -->
    <div id="editGoalModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeGoalEditModal()">&times;</span>
            <h3>Edit Savings Goal</h3>
            <form id="editGoalForm" method="POST" action="edit.php">
                <input type="hidden" name="id" id="edit-goal-id">

                <label>Goal Name:</label><br>
                <input type="text" name="goal_name" id="edit-goal-name" required><br>

                <label>Target Amount:</label><br>
                <input type="number" name="target_amount" id="edit-goal-target" required><br>

                <label>Saved Amount:</label><br>
                <input type="number" name="saved_amount" id="edit-goal-saved" required><br>

                <label>Start Date:</label><br>
                <input type="date" name="start_date" id="edit-goal-start" required><br>

                <label>End Date:</label><br>
                <input type="date" name="end_date" id="edit-goal-end" required><br>

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Add Savings Goal Collapsible Form -->
    <div class="add-income-container">
        <button class="toggle-form-btn" onclick="toggleForm()">
            <i class="fas fa-chevron-down"></i> Add New Goal
        </button>

        <div id="collapsibleForm" class="collapsible-form">
            <form method="POST" action="add.php">
                <label>Goal Name:</label><br>
                <input type="text" name="goal_name" required><br>

                <label>Target Amount:</label><br>
                <input type="number" name="target_amount" required><br>

                <label>Saved Amount:</label><br>
                <input type="number" name="saved_amount" required><br>

                <label>Start Date:</label><br>
                <input type="date" name="start_date" required><br>

                <label>End Date:</label><br>
                <input type="date" name="end_date" required><br>

                <button type="submit">Add Goal</button>
            </form>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['messages'])): ?>
    <div class="flash-toast"><?= $_SESSION['messages'][0]; ?></div>
    <?php unset($_SESSION['messages']); ?>
<?php endif; ?>

<script src="../assets/js/script.js"></script>

</body>
</html>
