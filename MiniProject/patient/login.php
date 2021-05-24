<?php require_once 'pdo.php';
session_start();

if(isset($_POST["patientid"]) && isset($_POST["pass"]))
{
  $_SESSION["patientid"] = $_POST["patientid"];
  $_SESSION["pass"] = $_POST["pass"];
  header("Location: login.php");
  return;
}

if(isset($_SESSION["patientid"]) && isset($_SESSION["pass"]))
{
	$username = $_SESSION["patientid"];
	$password = $_SESSION["pass"];
	
	if(strlen($username) < 1 || strlen($password) < 1)
        $_SESSION["error"] = "Patient ID and password are required";
    else if(!is_numeric($username))
        $_SESSION["error"] = "Patient ID must be numeric";
	$stmt = $pdo-> prepare("SELECT * FROM patient_info WHERE patientid = :pat_id ");
    $stmt-> execute(array(":pat_id" => $username));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if($stmt -> rowcount() < 1)
		$_SESSION["error"] = "Invalid patient ID";
	else
	{
		if($password != $row["pat_password"])
			$_SESSION["error"] = "Incorrrect password";
		else
		{
			$_SESSION['name'] = $row['doc_fname'];
			header("Location: appointmentsdisplay.php");
			return;
		}
	}
	unset($_SESSION["pass"]);
	unset($_SESSION["patientid"]);
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Patient login</title>
    <link rel="icon" href="/miniproject/favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <?php require_once "bootstrap.php"?>
  </head>
  <body class = "bgcolor">
    <nav id=nav1 class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
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
          <a class="nav-link" href="/miniproject/doctor/doclogin.php">DOCTOR</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php">PATIENT</a>
        </li>
      </ul>
      </div>
    </nav>
    <div class = "container" style="width: 450px;">
      <div class="card">
              <div class="card-body">
      <h1>Please Log In</h1>
      <?php
        if(isset($_SESSION["error"]))
        {
            echo('<p style = "color:red;">' . $_SESSION["error"] . "</p>\n");
            unset($_SESSION["error"]);
        }
      ?>
    <form method = "POST" action = "login.php">
        <input type = "text" name = "patientid"  placeholder="Patient ID" size="30"><br>
        <input type = "password" name = "pass"  placeholder="Password" size="30"><br>
        <input type = "submit" value = "LogIn">
        <input type= "submit" name="cancel" value = "Cancel">
    </form>
  </div>
</div>
</div>
  </body>
</html>
