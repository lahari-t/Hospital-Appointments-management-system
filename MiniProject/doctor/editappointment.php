<?php
require_once 'pdo.php';
session_start();
//echo($_GET["app_id"]);
$_SESSION["app_id"] = $_GET["app_id"];
echo($_SESSION["app_id"]);
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
  header("Location: editappointment.php?app_id=" . $_POST["app_id"]);
  return;
}
if(isset($_SESSION["appointment_date"]) && isset($_SESSION["appointment_start_time"]) && isset($_SESSION["appointment_end_time"]) && isset($_SESSION["app_id"]) )
{
  $appointment_date = $_SESSION["appointment_date"];
  $appointment_start_time = $_SESSION["appointment_start_time"];
  $appointment_end_time = $_SESSION["appointment_end_time"];
  $app_id = $_SESSION["app_id"];
  if(strlen($appointment_date)< 1 || strlen($appointment_start_time)< 1 || strlen($appointment_end_time)< 1 )
      $_SESSION["failure"] = "All fields are required";
  else
  {
    $sql= "UPDATE appointment SET appointment_date = :appdate, appointment_start_time = :appsttime, appointment_end_time = :appendtime WHERE app_id = :a_id";
    $stmt = $pdo-> prepare($sql);
    $stmt->execute(array(":appdate" => $appointment_date, ":appsttime" => $appointment_start_time, ":appendtime" => $appointment_end_time, ":a_id" => $_GET["app_id"]));
    $_SESSION["success"] = "RECORD UPDATED SUCCESSFULLY";
    unset($_SESSION["appointment_date"]);
    unset($_SESSION["appointment_start_time"]);
    unset($_SESSION["appointment_end_time"]);
    unset($_SESSION["app_id"]);

    header("Location: createappointment.php");
    return;
  }
  unset($_SESSION["app_id"]);
  unset($_SESSION["appointment_date"]);
  unset($_SESSION["appointment_start_time"]);
  unset($_SESSION["appointment_end_time"]);

}
if(!isset($_GET["app_id"]))
{
      $_SESSION["error"] = "Missing id";
      header("Location: createappointment.php");
      return;
}
$stmt = $pdo->prepare("SELECT * FROM appointment WHERE app_id = :xyz");
$stmt->execute(array(":xyz" => $_GET["app_id"]));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if($row === false)
{
      $_SESSION["error"] = "invalid appointment ID";
      header("Location: createappointment.php");
      return;
}
$app_id2 = $row["app_id"];
$appointment_date2 = htmlentities($row["appointment_date"]);
$appointment_start_time2 = htmlentities($row["appointment_start_time"]);
$appointment_end_time2 = htmlentities($row["appointment_end_time"]);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Update Appointment</title>
    <link rel="icon" href="/miniproject/favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <?php require_once "bootstrap.php"?>
  </head>
  <body class="bgcolor">
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
          <a class="nav-link" href="#">PATIENT</a>
        </li>
      </ul>
      </div>
    </nav>
    <div class = "container">
        <div class="card">
                <div class="card-body">
        <h3>UPDATE THE SCHEDULE</h3>
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
        <form method = "post" action="editappointment.php">
               <p>
                    SCHEDULE DATE:
                    <input type = "date" name = "appointment_date" value= "<?php echo($appointment_date2)?>" size = "40">
              </p>
              <p>
                    START TIME:
                    <input type = "time" name = "appointment_start_time" value= "<?php echo($appointment_start_time2)?>" size = "40">
              </p>
              <p>
                    END TIME:
                    <input type = "time" name = "appointment_end_time" value= "<?php echo($appointment_end_time2)?>" size = "40">
              </p>
		      <p>
					<input type = "hidden" name = "app_id" value = "<?php echo($app_id2)?>">
			  </p>
              <input type = "submit"  value = "Add">
              <input type = "submit" name = "cancel" value = "cancel">
            </form>
    </div>
  </div>
  </div>

  </body>
</html>
