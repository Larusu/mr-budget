<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Mr_Budget</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css" />
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
        <p class="small-text">Not a member? <a href="auth/register.php">Register here</a></p>
        <h2>Welcome Back!</h2>
        <p class="sub-text">We're happy to see you again.</p>
      </div>

      <?php if (isset($_SESSION['messages'])): ?>
        <?php foreach ($_SESSION['messages'] as $message): ?>
          <div class="message">
                <span><?= htmlspecialchars($message) ?></span>
            </div>
        <?php endforeach; unset($_SESSION['messages']); ?>
      <?php endif; ?>

      <form action="auth/login.php" method="POST">
        <input 
            type="email" 
            name="email" 
            placeholder="Enter your email" 
            value="<?php echo isset($_SESSION['old_email']) ? htmlspecialchars($_SESSION['old_email']) : ''; ?>" 
            required 
        />
        
        <div class="password-wrapper">
          <input type="password" id="password" name="password" placeholder="Password" required />
          <button type="button" class="toggle-password" onclick="togglePassword(this)">
            <i class="fa-solid fa-eye-slash"></i>
          </button>
        </div>

        <button type="submit" class="login-btn">Log In</button>
        </form>

        <?php unset($_SESSION['old_email']); ?>
    </div>
  </div>

  <script src="assets/js/script.js"></script>
</body>
</html>
