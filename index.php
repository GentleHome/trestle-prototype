<?php
session_start();
if(isset($_SESSION['user_id'])){
    header("Location: ./landing.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="index.css" />
  <script src="login.js"></script>
  <title>Trestle Login | Sign Up</title>
</head>

<body>
  <!--Container-->
  <div class="container">
    <div class="filter"></div>
    <div class="forms-container">
      <div class="signin-signup">
        <!--Login Form-->
        <form class="sign-in-form" autocomplete="off" method="POST" onsubmit="loginUser(); return false">
          <h2 class="title">Login</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" id="login-user" name="username" placeholder="Username" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" id="login-pass" name="password" placeholder="Password" />
          </div>
          <div id="return-message" style="color: red;"></div>
          <input type="submit" name="login" id="login-btn" value="Login" class="btn solid" />
        </form>
        <!--Register Form-->
        <form class="sign-up-form" autocomplete="off" method="POST" onsubmit="registerUser(); return false">
          <h2 class="title">Sign up</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" id="reg-username" name="username" placeholder="Username" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" id="reg-pass" name="password1" placeholder="Password" onkeyup="checkPassword()"
              required />
          </div>
          <div class="input-field">
            <i class="fas fa-user-lock"></i>
            <input type="password" id="reg-cpass" name="password2" placeholder="Confirm Password"
              onkeyup="checkPassword()" required />
            <div id="password-message" style="color: red;"></div><br>
          </div>
          <input type="submit" name="register" class="btn" value="Sign-up" />
        </form>
      </div>
    </div>
    <!--Panels-->
    <div class="panels-container">
      <div class="panel left-panel">
        <div class="content">
          <h3>Welcome to Trestle</h3>
          <p>
            Not yet a member?<br>Fill some basic information to get started.
          </p>
          <button class="btn transparent" id="sign-up-btn">
            Sign up
          </button>
        </div>
        <img src="images/login.png" class="image" alt="">
      </div>
      <div class="panel right-panel">
        <div class="content">
          <h3>Already a Trestler?</h3>
          <p>
            Let's go to login<br>and see what tasks await you.
          </p>
          <button class="btn transparent" id="sign-in-btn">
            Login
          </button>
        </div>
        <img src="images/register.png" class="image" alt="">
      </div>
    </div>
  </div>
  <!--Script-->
  <script src="index.js"></script>
</body>

</html>