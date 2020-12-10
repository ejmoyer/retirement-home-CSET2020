<?php
session_start();

$mysqli = new mysqli('localhost', 'root', '', 'retirement');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
// check access
if ($_SESSION['access'] != 3) {
  header('Location: ../home.html');
  exit;
}
$currentDate = date("Y-m-d");
// logout button
echo <<<EOT
<form action="../authentication/logout.php" method="get">
<input type=submit value=Logout>
</form>
EOT;

// get the doctor's id
if ($stmt = $mysqli->prepare("SELECT employeeId FROM employees where userId = ?")) {
  $stmt->bind_param("i", $_SESSION['user']);
  $stmt->execute();
  $stmt->bind_result($empId);
  while ($stmt->fetch()) {
    $doctorId = $empId;
  }
  $stmt->close();
}
?>
