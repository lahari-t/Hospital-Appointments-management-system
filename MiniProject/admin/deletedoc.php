<?php
  require_once 'pdo.php';
  session_start();
  if(isset($_POST["delete"]) && isset($_POST["doc_id"]))
  {
    $_SESSION["delete"] = $_POST["delete"];
    $_SESSION["doc_id"] = $_POST["doc_id"];
    header("Location: deletedoc.php?doc_id=" . $_POST["doc_id"]);
    return;
  }
  if(isset($_SESSION["delete"]) && isset($_SESSION["doc_id"]))
  {
    $sql = "DELETE FROM doctor WHERE doc_id = :xyz";
    $stmt = $pdo-> prepare($sql);
    $stmt->execute(array(":xyz" => $_SESSION["doc_id"]));
    $_SESSION["success"] = "Record deleted";
    unset($_SESSION["delete"]);
    unset($_SESSION["doc_id"]);
    header("Location: adminadd.php");
    return;
  }
  if(!isset($_GET["doc_id"]))
  {
      $_SESSION["error"] = "Missing id";
      header("Location: adminadd.php");
      return;
  }
  $stmt = $pdo->prepare("SELECT doc_fname,doc_lname, doc_id FROM doctor WHERE doc_id = :xyz");
  $stmt->execute(array(":xyz"=> $_GET["doc_id"]));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if($row === false)
  {
    $_SESSION["error"] = "INVALID ID";
    header("Location: adminadd.php");
  }

  $fname =  $row["doc_fname"];
  $lname =  $row["doc_lname"];
  $doc_id = $row["doc_id"];
?>

<!DOCTYPE html>
<html lang = "en">
<head>
  <meta charset = "utf-8">
  <title>deleting a record </title>
  <?php require_once "bootstrap.php" ?>
</head>
<body>
  <div class = "container" style = "width: 550px; margin-top: 20px; margin: auto;">
    <div class="card">
		<div class="card-body">
	<p>
      <h6>CONFIRM: ARE YOU SURE YOU WANT TO DELETE RECORD FOR </h6><br> <?php echo("Dr. " . $fname ." ". $lname);?>
    </p>
    <form method = "post">
      <input type = "hidden" name = "doc_id" value = <?php echo('"' . $doc_id . '"')?>>
      <input type = "submit" name = "delete" value = "Delete">
      <a href= "adminadd.php"> Cancel </a>
    </form>
	</div>
	</div>
  </div>
  </body>
</html>
