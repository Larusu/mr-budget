<?php
require_once '../helpers/auth.php';
require_login();

// Flash messages
if (!empty($_SESSION['messages'])): ?>
    <div class="message-container">
        <?php foreach ($_SESSION['messages'] as $msg): ?>
            <div class="message">
                <span><?= htmlspecialchars($msg) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['messages']); ?>
<?php endif; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<!-- Sidebar Navigation -->
<aside class="sidebar">
  <div class="sidebar-header">
    <div class="app-title"><i class="fas fa-piggy-bank"></i> Mr. Budget</div>
  </div>

  <nav class="sidebar-nav">
    <ul>
      <li><a href="../dashboard/index.php"><i class="fa-solid fa-house-user"></i></i> Dashboard</a></li>
      <li><a href="../income/list.php"><i class="fas fa-wallet"></i> Income</a></li>
      <li><a href="../expenses/list.php"><i class="fas fa-money-bill-wave"></i> Expenses</a></li>
      <li><a href="../savings_goals/list.php"><i class="fas fa-bullseye"></i> Goals</a></li>
      <li><a href="../charts/financial_comparison.php"><i class="fas fa-chart-line"></i> Financial Comparison</a></li>
      <li><a href="../charts/goal_progress.php"><i class="fas fa-chart-pie"></i> Goals Progress</a></li>
      <li><a href="../dashboard/profile.php"><i class="fas fa-user"></i> Profile</a></li>
      <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>
</aside>

