<?php
require_once 'pdo.php';
session_start();
$alt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';

if(isset($_POST["doctorid"]) && isset($_POST["pass"]))
{
  $_SESSION["doctorid"] = $_POST["doctorid"];
  $_SESSION["pass"] = $_POST["pass"];

  header("Location: doclogin.php");
  return;
}

if(isset($_SESSION["doctorid"]) && isset($_SESSION["pass"]))
{
    $username = $_SESSION["doctorid"];
    $password = $_SESSION["pass"];
    $check = hash("md5" , $alt.$password);

    if(strlen($username) < 1 || strlen($password) < 1)
        $_SESSION["error"] = "DoctorID and password are required";
    else if(!is_numeric($username))
        $_SESSION["error"] = "Doctor ID must be numeric";
    else if($password != "php123")
        $_SESSION["error"] = "Incorrect password";
    else
    {
      // $stmt = $pdo->prepare("SELECT * FROM doctor WHERE doc_id = :xyz");
      // $stmt->execute(array(":xyz" => $_GET["doc_id"]));
      // $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $stmt = $pdo-> prepare('SELECT doc_fname,doc_lname from doctor WHERE doc_id = :doctor_id');
      $stmt->execute(array(':doctor_id' => $username));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if($row !== false)
      {
        $_SESSION['name'] = $row['doc_fname'];
        header("Location: createappointment.php");
        return;
      }
      else
      {
          $_SESSION["error"] = "Incorrect Doctor ID";
      }
    }
    //unset($_SESSION["doctorid"]);
    unset($_SESSION["pass"]);
}
//session_destroy();
?>







<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Doctor Login</title>
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
          <a class="nav-link" href="/miniproject/admin/admin.php">ADMIN</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="doclogin.php">DOCTOR</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/miniproject/patient/register.php">PATIENT</a>
        </li>
      </ul>
      </div>
    </nav>
    <div class = "container" style="width: 350px;">
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
    <form method = "POST" action = "doclogin.php">
        <p><input type = "text" name = "doctorid" id = "doctorid" placeholder="Doctor ID" size="30"><br></p>
        <p><input type = "password" name = "pass" id = "id_1723" placeholder="Password" size="30"><br></p>
        <input type = "submit" value = "LogIn">
        <input type= "submit" name="cancel" value = "Cancel">
    </form>
  </div>
</div>
</div>
</body>
</html>
