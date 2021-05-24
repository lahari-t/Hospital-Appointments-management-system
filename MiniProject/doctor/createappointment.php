<?php
require_once 'pdo.php';
session_start();
$doctor_id = $_SESSION['doctorid'];

$stmt = $pdo-> prepare('SELECT doc_fname,doc_lname from doctor WHERE doc_id = :do_id');
$stmt->execute(array(':do_id' => $doctor_id));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(isset($_POST["cancel"]))
{
    header("Location: createappointment.php");
    return;
}
if(isset($_POST["appointment_date"]) && isset($_POST["appointment_start_time"]) && isset($_POST["appointment_end_time"]))
{
  $_SESSION["appointment_date"] = $_POST["appointment_date"];
  $_SESSION["appointment_start_time"] = $_POST["appointment_start_time"];
  $_SESSION["appointment_end_time"] = $_POST["appointment_end_time"];
  header("Location: createappointment.php");
  return;
}
if(isset($_SESSION["appointment_date"]) && isset($_SESSION["appointment_start_time"]) && isset($_SESSION["appointment_end_time"]))
{
  $appointment_date = $_SESSION["appointment_date"];
  $appointment_start_time = $_SESSION["appointment_start_time"];
  $appointment_end_time = $_SESSION["appointment_end_time"];
  unset($_SESSION["appointment_date"]);
  unset($_SESSION["appointment_start_time"]);
  unset($_SESSION["appointment_end_time"]);
  if(strlen($appointment_date)< 1 || strlen($appointment_start_time)< 1 || strlen($appointment_end_time)< 1 )
      $_SESSION["failure"] = "All fields are required";
  else
  {
    $sql= "INSERT INTO appointment(appointment_date,appointment_start_time,appointment_end_time,doctor_id)VALUES(:appdate,:appsttime,:appendtime, :doc_id)";
    $stmt = $pdo -> prepare($sql);
    $stmt->execute(array(":appdate" => $appointment_date, ":appsttime" => $appointment_start_time, ":appendtime" => $appointment_end_time, ":doc_id" => $doctor_id));
    $_SESSION["success"] = "APPOINTMENT ADDED";
    header("Location: createappointment.php");
    return;
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo("Dr. " . $row['doc_fname'] . " " . $row['doc_lname']);?></title>
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
          <a class="nav-link" href="doclogin.php">DOCTOR</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">PATIENT</a>
        </li>
      </ul>
      </div>
    </nav>
    <form action="logout.php">
        <button id="newDoc" type="submit"  class="btn btn-dark">LOGOUT</button>
    </form>
    <div class = "container" style = "width: 450px;">
        <div class="card">
                <div class="card-body">
        <h3>CREATE A SCHEDULE</h3>
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
                    SCHEDULE DATE:
                    <input type = "date" name = "appointment_date" size = "40">
              </p>
              <p>
                    START TIME:
                    <input type = "time" name = "appointment_start_time" size = "40">
              </p>
              <p>
                    END TIME:
                    <input type = "time" name = "appointment_end_time" size = "40">
              </p>

              <input type = "submit"  value = "Add">
              <input type = "submit" name = "cancel" value = "cancel">
            </form>
    </div>
  </div>
  </div>
   
  <div class="container">
  <br>
  <hr>
  <br>
  <h3>YOUR NUMBER OF APPOINTMENTS PER DAY</h3>
  <?php
  //$Today_date = date('Y-m-d');
  $stmt = $pdo-> prepare("SELECT * FROM no_of_appointments WHERE doctor_id = :docid "); //AND appointment_date = :dat");
  $stmt->execute(array(':docid' => $doctor_id)); //, ':dat' => $Today_date));
  if($stmt -> rowCount() > 0)
  {
    echo('<table class = "table" 	style = "width: 50%; margin: auto;">' . "\n");
    echo("<tr><th>DATE</th><th>NUMBER OF APPOINTMENTS</th></tr>");
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC))
    {
		echo"<tr><td>";
        echo(htmlentities($row["appointment_date"]));
        echo"</td><td>";
        echo(htmlentities($row["app_count"]));
        echo "</td></tr>\n";
    }
    echo ("</table>");
  }
  else
  {
	  echo("<p style = 'text-align: center;'>" . "NO APPOINTMENTS SCHEDULED" . "</p>");
  }
  
  ?>
  <br>
  <hr>
  <br>
  <!--<div class="container"> -->
  <h3>Your Scheduled appointments:</h3>
  <?php
  $stmt = $pdo-> prepare("SELECT a.app_id,a.appointment_date, a.appointment_start_time, a.appointment_end_time, d.doc_fname,d.doc_lname FROM appointment a, doctor d WHERE d.doc_id = :docid AND a.doctor_id = d.doc_id AND a.pat_id IS NULL ORDER BY a.appointment_date");
  $stmt->execute(array(':docid' => $doctor_id));
  if($stmt -> rowCount() > 0)
  {
    echo('<table class = "table">' . "\n");
    echo("<tr><th>DOCTOR NAME</th><th>DATE OF APPOINTMENT</th><th>START TIME</th><th>END TIME</th><th>MODIFY</th></tr>");
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC))
    {
		$date = $row["appointment_date"];
		if($date < date('Y-m-d'))
		{
			continue;
		}
        echo"<tr><td>";
        echo(htmlentities("Dr. " . $row["doc_fname"] . " " . $row["doc_lname"] ));
        echo"</td><td>";
        echo(htmlentities($row["appointment_date"]));
        echo"</td><td>";
        echo(htmlentities($row["appointment_start_time"]));
        echo"</td><td>";
        echo(htmlentities($row["appointment_end_time"]));
        echo"</td><td>";
        echo('<a href = "editappointment.php?app_id=' . $row["app_id"] . '">Edit</a> | ');
        echo('<a href = "deleteappointment.php?app_id=' . $row["app_id"] . '">Delete</a>');
        echo "</td></tr>\n";
    }
    echo ("</table>");
  }
  else
  {
	  echo("<p style = 'text-align: center;'>" . "NO SCHEDULED APPOINTMENTS" . "</p>");
  }
  
  ?>
  <br>
  <hr>
  <br>
  <h3>Your Booked Appointments</h3>
  <?php
    $stmt = $pdo-> prepare("SELECT * FROM appointment a, doctor d , patient_info p WHERE a.doctor_id = :did AND a.pat_id IS NOT NULL AND a.doctor_id = d.doc_id AND a.pat_id = p.Patientid ORDER BY a.appointment_date;");
    $stmt->execute(array(":did" => $doctor_id));
    if($stmt -> rowCount() > 0)
    {
      echo('<table class = "table">' . "\n");
      echo("<tr><th>PATIENT NAME</th><th>DATE OF APPOINTMENT</th><th>START TIME</th><th>END TIME</th><th>PATIENT GENDER</th><th>ADD PRESCRIPTION</th><th>PRESCRIPTION STATUS</th></tr>");
      while($row = $stmt -> fetch(PDO::FETCH_ASSOC))
      {
		  $current_app_id = $row["app_id"];
          echo"<tr><td>";
          echo(htmlentities($row["patient_fname"] . " " . $row["patient_lname"] ));
          echo"</td><td>";
          echo(htmlentities($row["appointment_date"]));
          echo"</td><td>";
          echo(htmlentities($row["appointment_start_time"]));
          echo"</td><td>";
          echo(htmlentities($row["appointment_end_time"]));
          echo"</td><td>";
          echo(htmlentities($row["patient_gender"]));
          echo "</td><td>";
          echo('<a href = "addprescription.php?app_id=' . $row["app_id"] . '">ADD</a>');
		  echo"</td><td>";
		  $stmt1 = $pdo-> prepare("SELECT * FROM prescription WHERE appointment_id = :aid");
		  $stmt1->execute(array(":aid" => $current_app_id));
		  if($stmt1 -> rowCount() > 0)
		  {
			  while($row1 = $stmt1 -> fetch(PDO::FETCH_ASSOC))
			  {	
			    echo("PRESCRIPTION ADDED");
			  }
		  }
		  else
		  {
			  echo("PRESCRIPTION NOT ADDED");
		  }
          echo "</td></tr>\n";
      }
      echo ("</table>");
    }
	else
	{
	  echo("<p style = 'text-align: center;'>" . "NO BOOKED APPOINTMENTS" . "</p>");
	}
  ?>
  <hr>
  <br>
</div>
  </body>
</html>
