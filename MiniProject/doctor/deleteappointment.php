<?php
  require_once "pdo.php";
  session_start();
  echo($_GET["app_id"]);
  $app_id = $_GET["app_id"];
  if(isset($_POST["delete"]) && isset($_GET["app_id"]))
  {
    $_SESSION["delete"] = $_POST["delete"];
    $_SESSION["app_id"] = $_GET["app_id"];
    header("Location: deleteappointment.php?app_id=" . $_GET["app_id"]);
    return;
  }
  if(isset($_SESSION["delete"]) && isset($_SESSION["app_id"]))
  {
    $sql = "DELETE FROM appointment WHERE app_id = :xyz";
    $stmt = $pdo-> prepare($sql);
    $stmt->execute(array(":xyz" => $_SESSION["app_id"]));
    $_SESSION["success"] = "Record deleted";
    unset($_SESSION["delete"]);
    unset($_SESSION["app_id"]);
    header("Location: createappointment.php");
    return;
  }
?>
<!DOCTYPE html>
<html lang = "en">
<head>
  <meta charset = "utf-8">
  <title>deleting an appointment </title>
  <?php require_once "bootstrap.php" ?>
</head>
<body>
  <div class = "container">
    <p>
      confirm: Deleting?
    </p>
    <form method = "post">
      <input type = "hidden" name = "app_id" value = <?php echo('"' . $app_id . '"')?>>
      <input type = "submit" name = "delete" value = "Delete">
      <a href= "createappointment.php"> Cancel </a>
    </form>
  </div>
  </body>
</html>
