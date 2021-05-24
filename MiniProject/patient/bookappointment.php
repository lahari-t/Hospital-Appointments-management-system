<?php
session_start();
require_once 'pdo.php';
$patientID = $_SESSION["patientid"];
$appointmentID = $_GET["app_id"];
if(isset($_POST["cancel"]))
  {
      header("Location: appointmentsdisplay.php");
      return;
  }
if(isset($_POST["book"]))
{
  $stmt = $pdo-> prepare("UPDATE appointment SET pat_id = :pid WHERE app_id = :aid");
  $stmt->execute(array(":aid" => $appointmentID, ":pid" => $patientID));
  header("Location: appointmentsdisplay.php");
  return;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>BOOK APPOINTMENT</title>
    <link rel="icon" href="/miniproject/favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <?php require_once "bootstrap.php"?>
  </head>
  <body class="bgcolor">
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
    <div class="container" style="width: 500px;">
      <?php
        $stmt = $pdo-> prepare("SELECT * FROM appointment a, doctor d WHERE app_id = :appid AND a.doctor_id = d.doc_id;");
        $stmt->execute(array(":appid" => $_GET["app_id"]));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

      ?>
      <h3>Booking an appointment with</h3>
      <h3><?php echo (htmlentities("Dr. " . $row["doc_fname"] . " " . $row["doc_lname"] )); ?></h3>
      <p>
        <div class = "card">
          <div class="card-body">
            <strong>Appointment date: </strong><?php echo(htmlentities($row["appointment_date"]));  ?><br>
            <strong>Appointment start time: </strong><?php echo(htmlentities($row["appointment_start_time"])); ?><br>
            <strong>Appointment end time: </strong> <?php echo(htmlentities($row["appointment_end_time"]));  ?><br>
            <strong>Doctor fee: </strong><?php echo(htmlentities($row["doc_fee"]));  ?><br>
            <br>
            <form method="post">
              <input type = "submit" name ="book" value = "Book">
              <input type = "submit" name = "cancel" value = "cancel">
            </form>
          </div>
        </div>
      </p>
    </div>
  </body>
</html>
