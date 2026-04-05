<?php
require_once '../helpers/auth.php';
require_login();
include_once '../config/database.php';

$monthlyQuery = $conn->prepare("
  SELECT 
    DATE_FORMAT(date, '%Y-%m') AS month,
    SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) AS income,
    SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) AS expense
  FROM (
    SELECT amount, date, 'income' as type FROM income WHERE user_id = ?
    UNION ALL
    SELECT amount, date, 'expense' as type FROM expenses WHERE user_id = ?
  ) AS combined
  GROUP BY month
  ORDER BY month
");
$monthlyQuery->bind_param("ii", $_SESSION['user_id'], $_SESSION['user_id']);
$monthlyQuery->execute();
$monthlyResult = $monthlyQuery->get_result();

$months = $incomes = $expenses = [];

while ($row = $monthlyResult->fetch_assoc()) {
    $months[] = $row['month'];
    $incomes[] = (float) $row['income'];
    $expenses[] = (float) $row['expense'];
}

$incomeQuery = $conn->prepare("SELECT SUM(amount) AS total_income FROM income WHERE user_id = ?");
$incomeQuery->bind_param("i", $_SESSION['user_id']);
$incomeQuery->execute();
$incomeResult = $incomeQuery->get_result()->fetch_assoc();
$totalIncome = $incomeResult['total_income'] ?? 0;

$expenseQuery = $conn->prepare("SELECT SUM(amount) AS total_expenses FROM expenses WHERE user_id = ?");
$expenseQuery->bind_param("i", $_SESSION['user_id']);
$expenseQuery->execute();
$expenseResult = $expenseQuery->get_result()->fetch_assoc();
$totalExpenses = $expenseResult['total_expenses'] ?? 0;

$goalsQuery = $conn->prepare("
  SELECT goal_name, saved_amount, target_amount
  FROM savings_goals
  WHERE user_id = ?
  AND target_amount > 0
  ORDER BY (saved_amount / target_amount) DESC
  LIMIT 3
");
$goalsQuery->bind_param("i", $_SESSION['user_id']);
$goalsQuery->execute();
$goalsResult = $goalsQuery->get_result();

$topGoals = [];
while ($row = $goalsResult->fetch_assoc()) {
    $progress = min(100, ($row['saved_amount'] / $row['target_amount']) * 100);
    $topGoals[] = [
        'name' => $row['goal_name'],
        'progress' => round($progress, 1)
    ];
}

$savingsQuery = $conn->prepare("SELECT SUM(saved_amount) AS total_saved, SUM(target_amount) AS total_target FROM savings_goals WHERE user_id = ?");
$savingsQuery->bind_param("i", $_SESSION['user_id']);
$savingsQuery->execute();
$savingsResult = $savingsQuery->get_result()->fetch_assoc();
$totalSaved = $savingsResult['total_saved'] ?? 0;
$totalTarget = $savingsResult['total_target'] ?? 0;
$savingsProgress = ($totalTarget > 0) ? ($totalSaved / $totalTarget * 100) : 0;

$netBalance = $totalIncome - $totalExpenses;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="dashboard-body">
  <?php include '../includes/header.php'; ?>

  <main class="main-content">
    <div class="main-panel">
      <!-- UPPER ROW: STATS -->
      <div class="upper-row">
        <div class="upper-row-boxes">
          <h6>Total Income</h6>
          <p>₱<?= number_format($totalIncome, 2) ?></p>
        </div>
        <div class="upper-row-boxes">
          <h6>Total Expenses</h6>
          <p>₱<?= number_format($totalExpenses, 2) ?></p>
        </div>
        <div class="upper-row-boxes">
          <h6>Net Balance</h6>
          <p class="<?= $netBalance >= 0 ? 'positive' : 'negative' ?>">₱<?= number_format($netBalance, 2) ?></p>
        </div>
        <div class="upper-row-boxes">
          <h6>Savings Progress</h6>
          <p><?= number_format($savingsProgress, 1) ?>%</p>
        </div>
      </div>

      <!-- MIDDLE ROW: CHART PLACEHOLDER -->
      <div class="middle-row">
        <div class="dashboard-card">
          <h6>Income vs Expenses</h6>
          <canvas id="dashboardPieChart"></canvas>
        </div>
        <div class="dashboard-card">
          <h6>Top Goals Progress</h6>
          <?php if (!empty($topGoals)): ?>
            <?php foreach ($topGoals as $index => $goal): ?>
              <div class="goal-item">
                <strong>#<?= $index + 1 ?> <?= htmlspecialchars($goal['name']) ?></strong>
                <div class="progress-container">
                  <div class="progress-bar" style="width: <?= $goal['progress'] ?>%;"></div>
                </div>
                <small><?= $goal['progress'] ?>%</small>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No goals yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- BOTTOM ROW: SHORTCUTS OR PLACEHOLDER -->
      <div class="bottom-row">
        <div class="dashboard-card">
          <h6>Monthly Income vs Expenses</h6>
          <canvas id="lineChart"></canvas>
        </div>
      </div>
    </div>
  </main>

  <script>
    const chartMonths = <?= json_encode($months) ?>;
    const chartIncomes = <?= json_encode($incomes) ?>;
    const chartExpenses = <?= json_encode($expenses) ?>;
    const totalIncome = <?= json_encode($totalIncome) ?>;
    const totalExpenses = <?= json_encode($totalExpenses) ?>;
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../assets/js/script.js"></script>
</body>
</html>
