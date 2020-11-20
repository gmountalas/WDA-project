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
    <title>Register</title>
  </head>
  <body data-pagetype="registration">
    <?php include '_header.php'; ?>
    <main class="register-container">
      <section class="hero">
          <form method="POST" class="registerForm" id="registerForm" action="actions/register.php">
            <h1 class="text-center">Registration Form</h1>
            <p class="text-center">Please fill in this form to create an account.</p>
            <hr>
            <?php if (!empty($_GET['error'])) { ?>
              <div class="error">Registration Error</div>
            <?php } ?>
            <div class="form-group registration-form">
              <label for="fullName">
                <!-- <span style="color: red;">*</span> -->
                Your Name
              </label>
              <input id="fullName" name="name" type="text" value="" placeholder="ex. John Doe" class=" box" required="required" autofocus>
            </div>
            <div class="form-group register-email">
              <label for="emailAddress">
                <!-- <span style="color: red;">*</span> -->
                Your E-mail Address
              </label>
              <input id="emailAddress" name="email" type="email" value="" placeholder="example@example.com" class=" box" required="required">
              <div class="c-validation email-error">
                Please enter a valid email address!
              </div>
            </div>
            <div class="form-group register-email-ver">
              <label for="emailAddressVer">
                <!-- <span style="color: red;">*</span> -->
                Verify E-mail Address
              </label>
              <input id="emailAddressVer" type="email" value="" placeholder="example@example.com" class=" box" required="required">
              <div class= "c-validation email-ver-error">
                Please enter a valid email address!
              </div>
              <div class= "c-validation email-match-error">
                The email addresses don't match!
              </div>
            </div>
            <div class="form-group password">
              <label for="formPassword">
                <!-- <span style="color: red;">*</span> -->
                Your Password
              </label>
              <input name="password" id="formPassword" type="password" value="" minlength="5" class=" box" required="required">
            </div>
            <div class="action text-center">
              <button id="submitReg" type="submit">Register</button>
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
