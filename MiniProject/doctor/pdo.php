<!-- <?php
$con = mysqli_connect("localhost:3307","root","root","hospital");

// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
?> -->
<?php
$pdo = new PDO('mysql:host=localhost;port=3307;dbname=hospital', 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
