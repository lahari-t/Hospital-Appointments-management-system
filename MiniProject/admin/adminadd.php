<?php
require_once 'pdo.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>MANAGE DOCTORS</title>
    <link rel="icon" href="/miniproject/favicon.ico">
    <link rel="stylesheet" href="styles.css">
    <?php require_once "bootstrap.php"?>
  </head>
  <body class = "bgcolor">
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
    <div class="admin header">
      <form action="addDoc.php">
        <button id="newDoc" type="submit"  class="btn btn-dark">ADD NEW DOCTOR</button>
      </form>
      <h3>Add or edit or remove doctors</h3>
    </div>
    <?php
    //$stmt = $pdo-> query("SELECT doc_id,doc_fname,doc_lname,doc_phone,doc_email,doc_fee,experience,doc_did FROM doctor ORDER BY doc_id");
    $stmt = $pdo-> query("SELECT doc.doc_id,doc.doc_fname,doc.doc_lname,doc.doc_phone,doc.doc_email,doc.doc_fee,doc.experience,dep.dname FROM doctor doc, department dep WHERE doc.doc_did = dep.d_id ORDER BY doc_id");
    if($stmt -> rowCount() > 0)
    {
      echo('<table class = "table">' . "\n");
      echo("<tr><th>DOCTOR ID</th><th>NAME</th><th>PHONE NUMBER</th><th>EMAIL</th><th>FEE</th><th>EXPERIENCE</th><th>DEPARTMENT NAME</th><th>MODIFY</th> </tr>");
      while($row = $stmt -> fetch(PDO::FETCH_ASSOC))
      {
          echo"<tr><td>";
          echo(htmlentities($row["doc_id"]));
          echo"</td><td>";
          echo(htmlentities("Dr. " . $row["doc_fname"] . " " . $row["doc_lname"] ));
          echo"</td><td>";
          echo(htmlentities($row["doc_phone"]));
          echo"</td><td>";
          echo(htmlentities($row["doc_email"]));
          echo"</td><td>";
          echo(htmlentities($row["doc_fee"]));
          echo"</td><td>";
          echo(htmlentities($row["experience"]));
          echo"</td><td>";
          echo(htmlentities($row["dname"]));
          echo"</td><td>";
          echo('<a href = "editdoc.php?doc_id=' . $row["doc_id"] . '">Edit</a> | ');
          echo('<a href = "deletedoc.php?doc_id=' . $row["doc_id"] . '">Delete</a>');
          echo "</td></tr>\n";
      }
      echo('<form action="logout.php">
          <button id="newDoc" type="submit"  class="btn btn-dark">LOGOUT</button>
        </form>');
    }
    ?>

  </body>
</html>
