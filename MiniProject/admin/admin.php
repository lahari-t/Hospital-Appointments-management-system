<?php
session_start();
$alt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';

if(isset($_POST["email"]) && isset($_POST["pass"]))
{
  $_SESSION["email"] = $_POST["email"];
  $_SESSION["pass"] = $_POST["pass"];

  header("Location: admin.php");
  return;
}

if(isset($_SESSION["email"]) && isset($_SESSION["pass"]))
{
    $username = $_SESSION["email"];
    $password = $_SESSION["pass"];

    if(strlen($username) < 1 || strlen($password) < 1)
        $_SESSION["error"] = "Username and password are required";
    else if(strpos($username, '@') === false)
  		$_SESSION["error"] = "Email must have an at-sign (@)";
    else if($username != "admin@login")
      $_SESSION["error"] = "Invalid email";
    else
    {
      $check = hash("md5" , $alt.$password);
      if($check == $stored_hash && $username === "admin@login")
      {
          header("Location: adminadd.php");
          error_log("Login success" . $username);
          return;
      }
      else
      {
          $_SESSION["error"] = "Incorrect Password";
          error_log("Login fail" . $username . "$check");
      }
    }
    unset($_SESSION["email"]);
    unset($_SESSION["pass"]);
}
session_destroy();
?>







<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin Login</title>
    <link rel="icon" href="/miniproject/favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <?php require_once "bootstrap.php"?>
  </head>
  <body class = "bgcolor">
    <nav id=nav1 class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="#">GREY-SLOAN CLINIC</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="/miniproject/index.html">HOME</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin.php">ADMIN</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/miniproject/doctor/doclogin.php">DOCTOR</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/miniproject/patient/register.php">PATIENT</a>
        </li>
      </ul>
      </div>
    </nav>
    <div class = "container" style = "Width: 345px">
      <div class="card">
              <div class="card-body">
      <h3>Please Log In</h3>
      <?php
        if(isset($_SESSION["error"]))
        {
            echo('<p style = "color:red;">' . $_SESSION["error"] . "</p>\n");
            unset($_SESSION["error"]);
        }
      ?>
    <form method = "POST" action = "admin.php">
        <p><input type = "text" name = "email" id = "email" placeholder="ADMIN ID" size="30" ><br></p>
        <p><input type = "password" name = "pass" id = "id_1723" placeholder="PASSWORD" size="30"><br></p>
        <p><input type = "submit" value = "LogIn">
        <input type= "submit" name="cancel" value = "Cancel"></p>
    </form>
  </div>
</div>
</div>
</body>
</html>
