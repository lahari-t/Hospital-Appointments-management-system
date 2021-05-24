<?php
require_once 'pdo.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Average Fee</title>
    <link rel="icon" href="/miniproject/favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <?php require_once "bootstrap.php"?>
  </head>
  <body  class = "bgcolor">
    <nav id=nav2 class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
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
    <div class="container">
    <h3>AVERAGE CONSULTATION CHARGES DEPARTMENT WISE</h3>
    <br>
    <?php
    $stmt = $pdo-> query("call dept_fees()");
    if($stmt -> rowCount() > 0)
    {
      echo('<table class = "table" style = "width: 350px; margin: auto">' . "\n");
      echo("<tr><th>DEPARTMENT</th><th>AVEREAGE FEE</th></tr>");
      while($row = $stmt -> fetch(PDO::FETCH_ASSOC))
      {
          echo"<tr><td>";
          echo(htmlentities($row["dname"]));
          echo"</td><td>";
          echo(htmlentities($row["avg_fees"]));
          echo "</td></tr>\n";
      }
    echo("</table>");
    }
    ?>
    </div>
  </body>
</html>
