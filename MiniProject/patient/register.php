<?php
require_once 'pdo.php';
session_start();

if(isset($_POST["cancel"]))
{
    header("Location: register.php");
    return;
}
// if(isset($_POST["patient_fname"]) && isset($_POST["patient_lname"]) && isset($_POST["patient_phno"]) && isset($_POST["patient_email"]) && isset($_POST["patient_dob"]) && isset($_POST["patient_aadhar"]) && isset($_POST["patient_gender"]) && isset($_POST["patient_address"]) && isset($_POST["pat_password"]) && isset($_POST["confirm_password"]))
// {
//   $_SESSION["patient_fname"] = $_POST["patient_fname"];
//   $_SESSION["patient_lname"] = $_POST["patient_lname"];
//   $_SESSION["patient_phno"] = $_POST["patient_phno"];
//   $_SESSION["patient_email"] = $_POST["patient_email"];
//   $_SESSION["patient_dob"] = $_POST["patient_dob"];
//   $_SESSION["patient_aadhar"] = $_POST["patient_aadhar"];
//   $_SESSION["patient_gender"] = $_POST["patient_gender"];
//   $_SESSION["patient_address"] = $_POST["patient_address"];
//   $_SESSION["confirm_password"] = $_POST["confirm_password"];
//   $_SESSION["pat_password"] = $_POST["pat_password"];
//   header("Location: register.php");
//   return;
// }
//if(isset($_SESSION["patient_fname"]) && isset($_SESSION["patient_lname"]) && isset($_SESSION["patient_phno"]) && isset($_SESSION["patient_email"]) && isset($_SESSION["patient_dob"]) && isset($_SESSION["patient_aadhar"]) && isset($_SESSION["gender"]) && isset($_SESSION["patient_address"]) && isset($_SESSION["pat_password"]) && isset($_SESSION["confirm_password"]))
if(isset($_POST["add"]))
{
  $patientid = $_POST["patientid"];
  $patient_fname = $_POST["patient_fname"];
  $patient_lname = $_POST["patient_lname"];
  $patient_phno = $_POST["patient_phno"];
  $patient_email = $_POST["patient_email"];
  $patient_dob = $_POST["patient_dob"];
  $patient_aadhar = $_POST["patient_aadhar"];
  $patient_gender = $_POST["patient_gender"];
  $patient_address = $_POST["patient_address"];
  $confirm_password = $_POST["confirm_password"];
  $pat_password = $_POST["pat_password"];
  // unset($_SESSION["patient_fname"]);
  // unset($_SESSION["patient_lname"]);
  // unset($_SESSION["patient_phno"]);
  // unset($_SESSION["patient_email"]);
  // unset($_SESSION["patient_dob"]);
  // unset($_SESSION["patient_aadhar"]);
  // unset($_SESSION["patient_gender"]);
  // unset($_SESSION["patient_address"]);
  // unset($_SESSION["confirm_password"]);
  // unset($_SESSION["pat_password"]);
  if(strlen($patientid) > 0)
  {
    $stmt = $pdo-> prepare("SELECT * FROM patient_info WHERE patientid = :pat_id ");
    $stmt-> execute(array(":pat_id" => $patientid));
    if($stmt -> rowcount() > 0)
    {
      $_SESSION["failure"] = "Patient ID already taken or registered";
      header("Location: register.php");
      return;
    }
  }
  if(strlen($patientid) < 1 || strlen($patient_fname) < 1 || strlen($patient_lname) < 1 || strlen($patient_phno) < 1 || strlen($patient_email) < 1 || strlen($patient_dob) < 1 || strlen($patient_aadhar) < 1 || strlen($patient_gender) < 1 || strlen($patient_address) < 1 || strlen($confirm_password) < 1 || strlen($pat_password) < 1)
  {
    $_SESSION["failure"] = "All fields are required";
  }
  else if(!is_numeric($patientid))
      $_SESSION["failure"] = "PatientID must be numeric";
  else if(!is_numeric($patient_phno))
      $_SESSION["failure"] = "Phone number must be numeric";
  else if(!is_numeric($patient_aadhar))
      $_SESSION["failure"] = "AADHAAR must be numeric";
  else if($confirm_password !== $pat_password)
      $_SESSION["failure"] = "passwords don't match";
  else if(strpos($patient_email, '@') === false)
      $_SESSION["failure"] = "Email must have an at-sign (@)";
  else
  {
    $sql = "INSERT INTO patient_info(patientid,patient_fname, patient_lname, patient_phno, patient_address,patient_gender,patient_email,patient_dob,patient_aadhar,pat_password) VALUES (:pid, :pf, :pl, :ph, :padd, :pg, :pe, :pdob, :pa, :ppass)";
    $stmt = $pdo -> prepare($sql);
    $stmt->execute(array(":pid"=> $patientid, ":pf" => $patient_fname, ":pl" => $patient_lname, ":ph" => $patient_phno, ":padd" => $patient_address, ":pg" => $patient_gender, ":pe" => $patient_email, ":pdob" => $patient_dob, ":pa" => $patient_aadhar, ":ppass" => $pat_password));
    $_SESSION["success"] = "Patient account created";
    header("Location: register.php");
    return;

  }

}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Patient Register</title>
    <link rel="icon" href="/miniproject/favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <?php require_once "bootstrap.php"?>
  </head>
  <body class = bgcolor>
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
    <div class = "container" style="width: 500px;">
        <div class="card">
                <div class="card-body">
        <h3>New? Register here</h3>
        <?php
        if(isset($_SESSION["failure"]))
        {
            echo('<p style = "color:red;">' . $_SESSION["failure"] . "</p>\n");
            unset($_SESSION["failure"]);
        }
        if(isset($_SESSION["success"]))
        {
            echo('<p style = "color:green;">' . $_SESSION["success"] . "</p>\n");
            unset($_SESSION["success"]);
        }
        ?>
        <form method = "post">
              <p>
                <input type = "text" name = "patientid" placeholder="PATIENT UNIQUE ID" size = "40">
              </p>
              <p>
                    <input type = "text" name = "patient_fname" placeholder="FIRST NAME" size = "40">
              </p>
              <p>
                    <input type = "text" name = "patient_lname" placeholder="LAST NAME" size = "40">
              </p>
              <p>
                    <input type = "text" name = "patient_phno" placeholder="PHONE NUMBER" size = "40">
              </p>
              <p>
                    <input type = "text" name = "patient_email" placeholder="email" size = "40">
              </p>
              <p>
                    DATE OF BIRTH:
                    <input type = "date" name = "patient_dob" size = "40">
              </p>
              <p>
                    <input type = "text" name = "patient_aadhar" placeholder="AADHAAR NUMBER" size = "40">
              </p>
              <p>
                    <input type="text" name="patient_gender" placeholder="GENDER(M/F)">
              </p>
              <p>
                    <textarea name="patient_address" rows="3" cols="43" placeholder="ADDRESS"></textarea>
              </p>
              <p>
                    <input type = "password" name = "pat_password" placeholder="CREATE PASSWORD" size = "40">
              </p>
              <p>
                    <input type = "password" name = "confirm_password" placeholder="CONFIRM PASSWORD" size = "40">
              </p>
              <p class="login-link">
                   Already have an account?
                   <a href="login.php"> Login</a>
               </p>
              <input type = "submit" name ="add" value = "add">
              <input type = "submit" name = "cancel" value = "cancel">
            </form>
    </div>
  </div>
  </div>


  </body>
</html>
