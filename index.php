<?php
  session_start();
  require_once('./include/connection.php');
?>
<html>
  <head>
    <title>Login and Reg</title>
    <style>
      .register{
        display: inline-block;
      }
      .login{
        display: inline-block;
        vertical-align: top;
        margin-left: 10px;
      }
      .errors{
        color: red;
      }
      .success{
        color: green;
      }
    </style>
  </head>
  <body>
    <h1>Login and Registration</h1>
    <div class="container">
      <?php
        if(isset($_SESSION["errors"])){
          foreach($_SESSION["errors"] as $error){
            ?><p class="errors"><?= $error ?></p><?php
          }
          unset($_SESSION["errors"]);
        }
      ?>

      <?php
        if(isset($_SESSION["success"]))
        {
          ?><p class="success"><?= $_SESSION["success"]?></p><?php
          unset($_SESSION["success"]);
        }
      ?>
      <div class="register">
        <form action="process.php" method="post">
          <input type="hidden" name="action" value="register">
          <p>First Name: <input type="text" name="first_name"></p>
          <p>Last Name: <input type="text" name="last_name"></p>
          <p>Email: <input type="text" name="email"></p>
          <p>Password: <input type="password" name="password"></p>
          <p>Confirm Password: <input type="password" name="confirm_password"></p>
          <input type="submit" value="Register">
        </form>
      </div>
      <div class="login">
        <form action="process.php" method="post">
          <input type="hidden" name="action" value="login">
          <p>Email: <input type="text" name="email"></p>
          <p>Password: <input type="password" name="password"></p>
          <input type="submit" value="Login">
        </form>
      </div>
    </div>
  </body>
</html>