<?php
  session_start();
  require_once 'pdo.php';
  $patient_id = $_SESSION["patientid"];
  $stmt = $pdo-> prepare('SELECT * from patient_info WHERE patientid = :pat_id');
  $stmt->execute(array(':pat_id' => $patient_id));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $patientid = $_SESSION["patientid"];
  $patient_fname = $row["patient_fname"];
  $patient_lname = $row["patient_lname"];
  if(isset($_POST["go"]))
  {
    //echo($_POST["dep_number"]);
    $department_number = $_POST["dep_number"];
  }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo ($patient_fname." ".$patient_lname); ?></title>
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
          <a class="nav-link" href="#">PATIENT</a>
        </li>
      </ul>
      </div>
    </nav>
    <div class="container">
      <form action="logout.php">
          <button id="newDoc" type="submit"  class="btn btn-dark">LOGOUT</button>
      </form>
    </div>

    <div class="container" style="width: 300px;">
    <div class = "card">
      <div class = "card-body">
          <form class ="departments" method="post">
            <fieldset>
              <p>
                <label>Select Department</label>
                <select name="dep_number" id = "myList">
                  <option value = "1">General Physician</option>
                  <option value = "2">Gynocology</option>
                  <option value = "3">Neurology</option>
                  <option value = "4">Dermetology</option>
                  <option value = "5">Pediatrics</option>
                  <option value = "6">Cardiology</option>
                  <option value = "7">Orthopedics</option>
                </select>
              </p>
            </fieldset>
            <input type = "submit" name="go" value = "GO">
          </form>
        </div>
      </div>
    </div>
      <br>
      <div class="container">
      <h3>DOCTORS IN OUR CLINIC</h3>
      <?php
      if(isset($department_number))
      {
      $stmt = $pdo-> prepare("SELECT doc.doc_id,doc.doc_fname,doc.doc_lname,doc.doc_phone,doc.doc_email,doc.doc_fee,doc.experience,dep.dname FROM doctor doc, department dep WHERE doc.doc_did = :dep AND doc.doc_did = dep.d_id ORDER BY doc_id");
      $stmt->execute(array(':dep' => $department_number));
      if($stmt -> rowCount() > 0)
      {
		
        echo('<table class = "table">' . "\n");
        echo("<tr><th>DOCTOR NAME</th><th>EXPERIENCE</th><th>FEE</th><th>Department</th></tr>");
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC))
        {
            echo"<tr><td>";
            echo(htmlentities("Dr. " . $row["doc_fname"] . " " . $row["doc_lname"] ));
            echo"</td><td>";
            echo(htmlentities($row["experience"]));
            echo"</td><td>";
            echo(htmlentities($row["doc_fee"]));
            echo"</td><td>";
            echo(htmlentities($row["dname"]));
            echo "</td></tr>\n";
        }
        echo "</table>";
      }
    }
      ?>
    </div>
    <br>
    <div class="container">
      <h3>APPOINTMENTS AVAILABLE</h3>
      <?php
      if(isset($department_number))
      {
        $stmt = $pdo-> prepare("SELECT * FROM appointment a, doctor d WHERE d.doc_did = :dep AND a.pat_id IS NULL AND a.doctor_id = d.doc_id;");
      $stmt->execute(array(':dep' => $department_number));
      if($stmt -> rowCount() > 0)
      {
		
        echo('<table class = "table">' . "\n");
        echo("<tr><th>DOCTOR NAME</th><th>DATE OF APPOINTMENT</th><th>START TIME</th><th>END TIME</th><th>BOOK</th></tr>");
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
            echo('<a href = "bookappointment.php?app_id=' . $row["app_id"] . '">BOOK</a>');
            echo "</td></tr>\n";
        }
        echo "</table>";
      }
    }
    ?>
    </div>
    <br>
    <h3>YOUR APPOINTMENTS</h3>
    <?php
    $stmt = $pdo-> prepare("SELECT * FROM appointment a, doctor d WHERE pat_id = :patid AND a.doctor_id = d.doc_id;");
    $stmt->execute(array(":patid" => $_SESSION["patientid"]));
    //$row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt -> rowCount() > 0)
    {
      while($row = $stmt -> fetch(PDO::FETCH_ASSOC))
      {
		$current_app_id = $row["app_id"];
        echo("<div class='container' style='width: 700px;'>");
        echo "<p>";
        echo("<div class = 'card'>");
        echo "<div class='card-body'>";
        echo ("<h4>You have an appointment with: " . htmlentities("Dr. " . $row["doc_fname"] . " " . $row["doc_lname"] ) . "</h4>");
        echo("<strong>Appointment date: </strong>" . htmlentities($row["appointment_date"]) . "<br>");
        echo("<strong>Appointment start time: </strong>" . htmlentities($row["appointment_start_time"]) . "<br>");
        echo("<strong>Appointment end time: </strong>" . htmlentities($row["appointment_end_time"]) . "<br>");
        echo("<strong>Doctor fee: </strong>" . htmlentities($row["doc_fee"]) . "<br>");
		$stmt1 = $pdo-> prepare("SELECT * FROM prescription WHERE appointment_id = :aid");
		$stmt1->execute(array(":aid" => $current_app_id));
		if($stmt1 -> rowCount() > 0)
		{
			while($row1 = $stmt1 -> fetch(PDO::FETCH_ASSOC))
			{
				echo("<p style = 'text-align: center; color:green;'>CONSULTATION COMPLETED</p>");
				echo("<strong>DIAGNOSIS: </strong><br>" . htmlentities($row1['diagnosis']) . "<br>");
				echo("<strong>MEDICINES </strong><br>" . htmlentities($row1["medicines"]) . "<br>");
				echo("<strong>SUGGESTED TESTS: </strong><br>" . htmlentities($row1["tests_required"]) . "<br>");
			}
		}
        echo "<br>";
        echo " </div>";
        echo "</div>";
        echo "</p>";
        echo "</div>";
      }
    }



    ?>
  </body>
</html>
