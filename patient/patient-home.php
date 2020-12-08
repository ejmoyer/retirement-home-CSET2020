<?php
session_start();

// if they are not a supervisor or admin
if ($_SESSION['access'] != 5) {
  header("Location: ../home.html");
  exit;
}
// create mysqli object
$mysqli = new mysqli('localhost', 'root', '', 'retirement');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

// logout button
echo <<<EOT
<form action="../authentication/logout.php" method="get">
<input type=submit value=Logout>
</form>
EOT;

if ($stmt = $mysqli->prepare("SELECT patientId, firstName, lastName FROM patients JOIN users ON patients.userId = users.id WHERE users.id = ?")) {
  $stmt->bind_param("i", $_SESSION['user']);
  $stmt->execute();
  $stmt->bind_result($patientId, $firstName, $lastName);
  while ($stmt->fetch()) {
  printf (<<<EOT
  <h1>Patient's Home</h1>
  <label for="patientId">Patient ID</label>
  <input type="text" name="patientId" value="%s" disabled>

  <label for="patientName">Patient Name</label>
  <input type="text" name="patientName" value="%s %s" disabled>

  <form action="patient-home.php" method="post">
  <label for="checkboxDate">Date</label>
  <input type="date" name="checkboxDate">

  <input type="submit">
  </form>
  EOT, $patientId, $firstName, $lastName);
  }
  $stmt->close();
}
?>
