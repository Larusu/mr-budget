<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        $_SESSION['messages'][] = "Passwords do not match.";
        header("Location: register.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows !== 0)
    {
      $_SESSION['messages'][] = "Email already exists.";
      header("Location: register.php");
      exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows !== 0)
    {
      $_SESSION['messages'][] = "Username already exists.";
      header("Location: register.php");
      exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashedPassword, $email);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! You can now login.";
        header("Location: register.php");
        exit();
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Mr_Budget</title>
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-body">
  <div class="login-panel">
    <div class="login-illustration">
      <div class="illustration-content">
        <svg class="piggy-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
          <path d="M320 32C373 32 416 75 416 128C416 181 373 224 320 224C267 224 224 181 224 128C224 75 267 32 320 32zM80 368C80 297.9 127 236.6 197.1 203.1C222.4 244.4 268 272 320 272C375.7 272 424.1 240.3 448 194C463.8 182.7 483.1 176 504 176L523.5 176C533.9 176 541.5 185.8 539 195.9L521.9 264.2C531.8 276.6 540.1 289.9 546.3 304L568 304C581.3 304 592 314.7 592 328L592 440C592 453.3 581.3 464 568 464L528 464C511.5 486 489.5 503.6 464 514.7L464 544C464 561.7 449.7 576 432 576L399 576C384.7 576 372.2 566.5 368.2 552.8L361.1 528L278.8 528L271.7 552.8C267.8 566.5 255.3 576 241 576L208 576C190.3 576 176 561.7 176 544L176 514.7C119.5 490 80 433.6 80 368zM456 384C469.3 384 480 373.3 480 360C480 346.7 469.3 336 456 336C442.7 336 432 346.7 432 360C432 373.3 442.7 384 456 384z"/>        </svg>
        <h2 class="app-title">Mr. Budget</h2>
      </div>
    </div>

    <div class="login-form">
      <div class="form-header">
        <p class="small-text-2">Already have an account? <a href="../index.php">Login here</a></p>
        <h2>Create Account</h2>
        <p class="sub-text">Start budgeting smarter today.</p>
      </div>

      <?php
      if (isset($_SESSION['success'])):
      ?>
        <script>
          alert("<?php echo $_SESSION['success']; ?>");
          window.location.href = "../index.php"; 
        </script>
        <?php unset($_SESSION['success']); ?>
      <?php
      endif;

      if (isset($_SESSION['messages'])):
        foreach ($_SESSION['messages'] as $message):
      ?>
          <p class="error"><?php echo htmlspecialchars($message); ?></p>
      <?php
        endforeach;
        unset($_SESSION['messages']);
      endif;
      ?>


      <form action="register.php" method="POST" onsubmit="return validatePasswords()">
        <input type="text" name="username" placeholder="Enter your username" required />
        <input type="email" name="email" placeholder="Enter your email" required />

        <div class="password-wrapper">
          <input type="password" id="password" name="password" placeholder="Password" required />
          <button type="button" class="toggle-password" onclick="togglePassword(this)">
            <i class="fa-solid fa-eye-slash"></i>
          </button>
        </div>

         <div class="password-wrapper">
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required />
          <button type="button" class="toggle-password" onclick="togglePassword(this)">
            <i class="fa-solid fa-eye-slash"></i>
          </button>
        </div>

        <p id="password-error" class="error hidden-error">Passwords do not match.</p>

        <button type="submit" class="register-btn">Register</button>
      </form>
    </div>
  </div>

  <script src="../assets/js/script.js"></script>
</body>
</html>
