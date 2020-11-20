<?php 

require __DIR__.'/../boot/boot.php';

use Hotel\User;

// Check for existing logged in user
if (!empty(User::getCurrentUserId())) {
  header('Location: ./index.php');
  return;
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <meta name="robots" content="noindex,nofollow">
    <link href="./assets/css/jquery-ui.css" rel="stylesheet" type="text/css">
    <script src="./assets/js/jquery-3.5.1.min.js"></script>
    <script src="./assets/js/jquery-3.5.1.js"></script>
    <script src="./assets/js/jquery-ui.js"></script>
    <script src="./assets/js/jquery.ui.touch-punch.min.js"></script>
    <link rel="shortcut icon" href="./assets/images/favicon/favicon.ico" />
    <title>Login</title>
  </head>
  <body data-pagetype="login">
    <?php include '_header.php'; ?>
    <main class="login-container">
      <section class="hero">
        <form name="loginForm" class="loginForm" id="loginForm" method="POST" action="actions/login.php">
          <h1 class="text-center">Login Form</h1>
          <p>Please fill in this form to login to your account.</p>
          <hr>
          <?php if (!empty($_GET['error'])) { ?>
            <div class="error"><?php echo $_GET['error']; ?></div>
          <?php } ?>
          <div class="form-group login-email">
            <label for="LoginemailAddress">
              <!-- <span style="color: red;">*</span> -->
              Your E-mail Address
            </label>
            <input name="email" id="LoginemailAddress" type="email" value="" placeholder="example@example.com" class=" box" required="required">
            <div class= "c-validation email-error">
              Please enter a valid email address!
            </div>
          </div>
          <div class="form-group password">
              <label for="LoginPassword">
                <!-- <span style="color: red;">*</span> -->
                Your Password
              </label>
              <input name="password" id="LoginPassword" type="password" value="" class=" box" minlength="5" required="required" >
          </div>
          <div class="action text-center">
            <button id="submitLogin" type="submit">Login</button>
          </div>
        </form>
      </section>
    </main>

    <?php include '_footer.php'; ?>

    <link href="./assets/css/styles.css" type="text/css" rel="stylesheet" />
    <link rel= "stylesheet" type="text/css" href="./assets/css/fontawesome.min.css" />
    <script src="./assets/js/script.js"></script>
  </body>
</html>
