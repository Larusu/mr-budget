<?php
include_once '../config/database.php';
require_once '../helpers/auth.php';
require_login();

$query = "SELECT * FROM income WHERE user_id = ?";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $id = intval($_POST['id']);

    if (isset($_POST['delete'])) 
    {
        $stmt = $conn->prepare("DELETE FROM income WHERE id = ?");
        $stmt->bind_param("i", $id); 
        if ($stmt->execute()) 
        {
            $_SESSION['messages'][] = "Deleted successfully!";
            header("Location: list.php");
            exit();
        } 
        else 
        {
            $_SESSION['messages'][] = "Delete failed!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Incomes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class ="income-body">

<?php include '../includes/header.php'; ?>

    <div class="income-layout">
        <table class="income-table">
                <thead>
                    <tr>
                        <th>Source</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Action</th> 
                    </tr>
                </thead>

                <tbody>
                <?php 
                    while ($row = mysqli_fetch_assoc($data)) {
                    $amount = $row['amount']; 
                    $totalAmount += $amount;
                    $count++;
                ?>
                    <tr>
                        <td><?= $row['source']; ?></td>
                        <td><?= $row['date']; ?></td>
                        <td>₱<?= number_format($amount, 2);?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="delete"><i class="fas fa-trash"></i></button>
                                </form>

                                <!-- Edit button becomes a standalone trigger -->
                                <button 
                                    type="button" 
                                    class="edit-btn" 
                                    data-id="<?= $row['id'] ?>" 
                                    data-source="<?= htmlspecialchars($row['source']) ?>" 
                                    data-amount="<?= $row['amount'] ?>" 
                                    data-date="<?= $row['date'] ?>" 
                                    onclick="openEditModal(this)"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                        </td>
                    </tr>

                <?php } ?>
                </tbody>

                <tfoot>
                    <?php if($count > 0):?>
                    <tr>
                        <td colspan="2"></td> <!-- Empty 2 columns-->
                        <td>₱<?= number_format($totalAmount, 2); ?></td> 
                        <td>Count: <?= $count; ?></td>
                    </tr>
                    <?php endif; ?>
                </tfoot>
        </table>

                <!-- Edit Modal -->
        <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeEditModal()">&times;</span>
            <h3>Edit Income Entry</h3>
            <form id="editForm" method="POST" action="edit.php">
            <input type="hidden" name="id" id="edit-id">

            <label>Source:</label><br>
            <input type="text" name="source" id="edit-source" required><br>

            <label>Amount:</label><br>
            <input type="number" name="amount" id="edit-amount" required><br>

            <label>Date:</label><br>
            <input type="date" name="date" id="edit-date" required><br>

            <button type="submit">Save Changes</button>
            </form>
        </div>
        </div>


        <div class="add-income-container">
                <!-- Toggle Button -->
            <button class="toggle-form-btn" onclick="toggleForm()">
                <i class="fas fa-chevron-down"></i> Add New Entry
            </button>
            
        
            <!-- Collapsible Form -->
            <div id="collapsibleForm" class="collapsible-form">
                <form method="POST" action="add.php">
                    <label>Source:</label><br>
                    <input type="text" name="source" required><br>

                    <label>Amount:</label><br>
                    <input type="number" name="amount" required><br>

                    <label>Date:</label><br>
                    <input type="date" name="date" required><br>

                    <button type="submit">Add Entry</button>
                </form>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['messages'])): ?>
        <div class="flash-toast"><?= $_SESSION['messages'][0]; ?></div>
        <?php unset($_SESSION['messages']); ?>
    <?php endif; ?>

</body>
    <script src="../assets/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</html>