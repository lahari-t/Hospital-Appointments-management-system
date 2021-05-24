<?php
require_once 'pdo.php';
session_start();
//echo($_GET["doc_id"]);
if(isset($_POST["cancel"]))
  {
      header("Location: adminadd.php");
      return;
  }
if(isset($_POST["doc_id"]) && isset($_POST["doc_fname"]) && isset($_POST["doc_lname"]) && isset($_POST["doc_phone"]) && isset($_POST["doc_email"]) && isset($_POST["doc_fee"]) && isset($_POST["experience"]) && isset($_POST["dept_id"]))
{
      $_SESSION["doc_id"] = $_POST["doc_id"];
      $_SESSION["doc_fname"] = $_POST["doc_fname"];
      $_SESSION["doc_lname"] = $_POST["doc_lname"];
      $_SESSION["doc_phone"] = $_POST["doc_phone"];
      $_SESSION["doc_email"] = $_POST["doc_email"];
      $_SESSION["doc_fee"] = $_POST["doc_fee"];
      $_SESSION["experience"] = $_POST["experience"];
      $_SESSION["dept_id"] = $_POST["dept_id"];
      header("Location: editdoc.php?doc_id=" . $_POST["doc_id"]);
      return;
}
if(isset($_SESSION["doc_id"]) && isset($_SESSION["doc_fname"]) && isset($_SESSION["doc_lname"]) && isset($_SESSION["doc_phone"]) && isset($_SESSION["doc_email"]) && isset($_SESSION["doc_fee"]) && isset($_SESSION["experience"]) && isset($_SESSION["dept_id"]))
{
  $doc_id = $_SESSION["doc_id"];
  $doc_fname = $_SESSION["doc_fname"];
  $doc_lname = $_SESSION["doc_lname"];
  $doc_phone = $_SESSION["doc_phone"];
  $doc_email = $_SESSION["doc_email"];
  $doc_fee = $_SESSION["doc_fee"];
  $experience = $_SESSION["experience"];
  $dept_id = $_SESSION["dept_id"];


  if(strlen($doc_id)< 1 || strlen($doc_fname)< 1 || strlen($doc_lname)< 1 || strlen($doc_phone)< 1 || strlen($doc_email)< 1 || strlen($doc_fee)< 1 || strlen($experience)< 1 || strlen($dept_id)< 1)
      $_SESSION["failure"] = "All fields are required";
  else if(!is_numeric($doc_id))
      $_SESSION["failure"] = "Doctor ID must be numeric";
  else if(!is_numeric($doc_phone))
      $_SESSION["failure"] = "Phone number must be numeric";
  else if(!is_numeric($doc_fee))
      $_SESSION["failure"] = "Fee amount must be numeric";
  else if(!is_numeric($experience))
      $_SESSION["failure"] = "Experience must be numeric";
  else
  {
    $sql = "UPDATE doctor SET doc_fname = :fname, doc_lname = :lname, doc_phone = :ph, doc_email = :email, doc_fee = :fee, experience = :expe, doc_did = :did WHERE doc_id = :id";
    $stmt = $pdo-> prepare($sql);
    $stmt->execute(array(":fname" => $doc_fname, ":lname" => $doc_lname, ":ph" => $doc_phone, ":email" => $doc_email, ":fee" => $doc_fee, ":expe" => $experience, ":did" => $dept_id, ":id" => $doc_id));
    $_SESSION["success"] = "RECORD UPDATED";
    unset($_SESSION["doc_id"]);
    unset($_SESSION["doc_fname"]);
    unset($_SESSION["doc_lname"]);
    unset($_SESSION["doc_phone"]);
    unset($_SESSION["doc_email"]);
    unset($_SESSION["doc_fee"]);
    unset($_SESSION["experience"]);
    unset($_SESSION["dept_id"]);

    header("Location: adminadd.php");
    return;
  }
  unset($_SESSION["doc_id"]);
  unset($_SESSION["doc_fname"]);
  unset($_SESSION["doc_lname"]);
  unset($_SESSION["doc_phone"]);
  unset($_SESSION["doc_email"]);
  unset($_SESSION["doc_fee"]);
  unset($_SESSION["experience"]);
  unset($_SESSION["dept_id"]);
}
if(!isset($_GET["doc_id"]))
{
      $_SESSION["error"] = "Missing id";
      header("Location: adminadd.php");
      return;
}
$stmt = $pdo->prepare("SELECT * FROM doctor WHERE doc_id = :xyz");
$stmt->execute(array(":xyz" => $_GET["doc_id"]));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row === false)
  {
      $_SESSION["error"] = "Bad value for id";
      header("Location: adminadd.php");
      return;
  }
$doctor_id = $row["doc_id"];
$firstname = htmlentities($row["doc_fname"]);
$lastname = htmlentities($row["doc_lname"]);
$number = htmlentities($row["doc_phone"]);
$emailid = htmlentities($row["doc_email"]);
$feeAmt = htmlentities($row["doc_fee"]);
$exper = htmlentities($row["experience"]);
$deptid = htmlentities($row["doc_did"]);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>EDIT DOCTOR</title>
    <link rel="icon" href="/miniproject/favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <?php require_once "bootstrap.php"?>
  </head>
  <body class= "bgcolor">
    <nav id=nav2 class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="#">GREY-SLOAN CLINIC</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.html">HOME</a>
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
    <div class = "container" style = "width: 450px;">
      <div class="card">
              <div class="card-body">
      <h3>EDIT THE VALUES</h3>
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
                  DOCTOR ID:
                  <input type = "text" name = "doc_id" size = "40" value= "<?php echo($doctor_id)?>">
            </p>
            <p>
                  FIRST NAME:
                  <input type = "text" name = "doc_fname" size = "40" value= "<?php echo($firstname)?>">
            </p>
            <p>
                  LAST NAME:
                  <input type = "text" name = "doc_lname" size = "40" value= "<?php echo($lastname)?>">
            </p>
            <p>
                  PHONE NUMBER:
                  <input type = "text" name = "doc_phone" size = "40" value= "<?php echo($number)?>">
            </p>
            <p>
                  EMAIL:
                  <input type = "text" name = "doc_email" size = "40" value= "<?php echo($emailid)?>">
            </p>
            <p>
                  FEE:<br>
                  <input type = "text" name = "doc_fee" size = "40" value= "<?php echo($feeAmt)?>">
            </p>
            <p>
                  EXPERIENCE:
                  <input type = "text" name = "experience" size = "40" value= "<?php echo($exper)?>">
            </p>
            <p>
                  DEPARTMENT:
                  <input type = "text" name = "dept_id" size = "40" value= "<?php echo($deptid)?>">
            </p>
            <input type = "submit" value = "Add">
            <input type = "submit" name = "cancel" value = "cancel">
          </form>
  </div>
</div>
</div>
<br>
  </body>
</html>
