<?php
  session_start();
  require_once('./include/connection.php');
  // var_dump($_SESSION);
  // var_dump($_POST);
  // die();

  if(isset($_POST["action"]) && $_POST['action'] == "create_message")
  {
    $message = escape_this_string($_POST["message"]);

    $query = "INSERT INTO messages (user_id, message, created_at) VALUES ({$_SESSION['user_id']}, '{$message}', NOW())";
    
    run_mysql_query($query);
    header("Location: wall.php");
    die();
  }

  if(isset($_POST["action"]) && $_POST['action'] == "create_comment")
  {
    $comment = escape_this_string($_POST["comment"]);

    $query = "INSERT INTO comments (user_id, message_id, comment, created_at) VALUES ({$_SESSION['user_id']}, {$_POST['message_id']}, '{$comment}', NOW())";
    run_mysql_query($query);
    header("Location: wall.php");
    die();

  }



  if(isset($_POST["action"]) && $_POST["action"] == "register")
  {
      register_user($_POST);
  }
  else if(isset($_POST["action"]) && $_POST["action"] == "login")
  {
      login_user($_POST);
  }
  else{
    session_destroy();
    header("Location: index.php");
    die();
  }


  function register_user($post)
  {
    if(empty($post["first_name"]))
    {
      $_SESSION["errors"][] = "first name can't be blank";
    }
    if(empty($post["last_name"]))
    {
      $_SESSION["errors"][] = "last name can't be blank";
    }
    if(empty($post["email"]))
    {
      $_SESSION["errors"][] = "email must be valid";
    }
    if(empty($post["password"]))
    {
      $_SESSION["errors"][] = "Please enter a password";
    }
    if($post["confirm_password"] !== $post["password"])
    {
      $_SESSION["errors"][] = "Passwords must match";
    }

    if(isset($_SESSION["errors"]) && count($_SESSION["errors"] > 0))
    {
      header("Location: index.php");
      die();
    }
    else
    {

      $fname = escape_this_string($post["first_name"]);
      $lname = escape_this_string($post["last_name"]);
      $email = escape_this_string($post["email"]);
      $password = escape_this_string(md5($post["password"]));


      $query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('{$fname}', '{$lname}', '{$email}', '{$password}', NOW(), NOW())";

      run_mysql_query($query);

      $_SESSION["success"] = "User has been registered";

      header("Location: index.php");
      die();
    }
  }

  function login_user($post)
  {
    $email = escape_this_string($post["email"]);
    $password = escape_this_string(md5($post["password"]));

    $query = "SELECT * FROM users WHERE users.email = '{$email}'";

    $user = fetch_record($query);

    if(!empty($user))
    {
      if($user["password"] == $password)
      {
        $_SESSION["first_name"] = $user["first_name"];
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["logged_in"] = TRUE;
        header('Location: wall.php');
        die();
      }
      else{
        bad_credentials();
      }
    }
    else
    {
      bad_credentials();
    }
  }

  function bad_credentials()
  {
    $_SESSION["errors"][] = "Invalid login, please try again";
    header("Location: index.php");
    die();
  }