<?php 
	require_once "pdo.php";
	session_start();
	//echo($_GET["app_id"]);
	
	$appointment_id = $_GET["app_id"];
	$stmt = $pdo-> prepare('SELECT * FROM DOCTOR D, patient_info P, appointment A WHERE D.doc_id = A.doctor_id AND A.pat_id = P.Patientid AND a.app_id = :a_id');
	$stmt->execute(array(':a_id' => $appointment_id));
	//while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	//{
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$patient_name = $row["patient_fname"] . " " .$row["patient_lname"];
	$doctor_name = "Dr. " . $row["doc_fname"] . " " . $row["doc_lname"];
	$patient_id = $row["pat_id"];
	$doctor_id = $row["doctor_id"];
	//}
	//echo($patient_id . $doctor_id . $appointment_id);
	if(isset($_POST["cancel"]))
	{
		header("Location: createappointment.php");
		return;
	}
	if(isset($_POST["diagnosis"]) && isset($_POST["medicine"]) && isset($_POST["suggested_tests"]))//  && isset($_POST["patient_id"]) && isset($_POST["doctor_id"]) && isset($_POST["appointment_id"]))
	{
		$_SESSION["diagnosis"] = $_POST["diagnosis"];
		$_SESSION["medicine"] = $_POST["medicine"];
		$_SESSION["suggested_tests"] = $_POST["suggested_tests"];
		$_SESSION["paid"] = $_POST["patient_id"]; //$patient_id;
		$_SESSION["doid"] = $_POST["doctor_id"];
		$_SESSION["app_id"] = $_POST["app_id"];
		header("Location: addprescription.php?app_id=" . $_POST["app_id"]);
		return;
	}
	if(isset($_SESSION["diagnosis"]) && isset($_SESSION["medicine"]) && isset($_SESSION["suggested_tests"]))// && isset($_SESSION["paid"]) && isset($_SESSION["doid"]) && isset($_SESSION["appoid"]))
	{
		$diagnosis = $_SESSION["diagnosis"];
		$medicine = $_SESSION["medicine"];
		$suggested_tests = $_SESSION["suggested_tests"];
		$appoid = $_SESSION["app_id"];
		$doid = $_SESSION["doid"];
		$paid = $_SESSION["paid"];
		
		if(strlen($diagnosis)< 1 && strlen($medicine)< 1 && strlen($suggested_tests)< 1 )
			$_SESSION["failure"] = "Any one of the field is required";
		else
		{
			//$sql2= "INSERT INTO prescription(medicines,diagnosis,tests_required,doctor_id,patient_id,appointment_id)VALUES(:med,:diag,:tests, :did, :pid, :aid)";
			$stmt2 = $pdo -> prepare("INSERT INTO prescription(medicines,diagnosis,tests_required,doctor_id,patient_id,appointment_id)VALUES(:med,:diag,:tests, :did, :pid, :aid)");
			$stmt2->execute(array(":med" => $medicine , ":diag" => $diagnosis, ":tests" => $suggested_tests, ":did" => $doid, ":pid" => $paid, ":aid" => $appoid));
			$_SESSION["success"] = "PRESCRIPTION ADDED";
			unset($_SESSION["diagnosis"]);
			unset($_SESSION["medicine"]);
			unset($_SESSION["suggested_tests"]);
			unset($_SESSION["doid"]);
			unset($_SESSION["appoid"]);
			unset($_SESSION["paid"]);
			header("Location: createappointment.php");
			return;
		}
		unset($_SESSION["diagnosis"]);
		unset($_SESSION["medicine"]);
		unset($_SESSION["suggested_tests"]);
		unset($_SESSION["doid"]);
		unset($_SESSION["appoid"]);
		unset($_SESSION["paid"]);
	}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>ADDING PRESCRIPTION</title>
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
    <div class = "container" style="width: 500px">
        <div class="card" >
                <div class="card-body" >
        <h4>APPOINTMENT SUMMARY FOR<br> <?php echo($patient_name);?></h4>
        <?php
          if(isset($_SESSION["failure"]))
          {
              echo('<p style = "color:red;">' . $_SESSION["failure"] . "</p>\n");
              unset($_SESSION["failure"]);
          }
        ?>
        <form method = "post">
               <p>
                    <textarea name="diagnosis" rows="3" cols="43" placeholder="DIAGNOSIS"></textarea>
              </p>
              <p>
                   <textarea name="medicine" rows="3" cols="43" placeholder="MEDICINES"></textarea>
              </p>
              <p>
                    <textarea name="suggested_tests" rows="3" cols="43" placeholder="SUGGESTED TESTS (IF ANY)"></textarea>
              </p>
			  <p>
					<input type = "hidden" name = "app_id" value = "<?php echo($appointment_id)?>">
			  </p>
			  <p>
					<input type = "hidden" name = "doctor_id" value = "<?php echo($doctor_id)?>">
			  </p>
			  <p>
					<input type = "hidden" name = "patient_id" value = "<?php echo($patient_id)?>">
			  </p>
              <input type = "submit" value = "Add">
              <input type = "submit" name = "cancel" value = "cancel">
            </form>
    </div>
  </div>
  </div>
  </body>
</html>
