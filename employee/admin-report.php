<?php
session_start();
if ($_SESSION['access'] != 1) {
  header('Location: ../home.html');
  exit;
}

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

echo <<<EOT
<h1>Admin Report</h1>

<form action="admin-report.php" method="post">
<input type="date" name="checkboxDate">

<input type="submit">
</form>
EOT;
if (isset($_POST['checkboxDate'])) {
  $patientsArray = [];

  if ($stmt = $mysqli->prepare("SELECT patientId FROM checkboxes WHERE checkboxDate = ?;")) {
    $stmt->bind_param("s", $_POST['checkboxDate']);
    $stmt->execute();
    $stmt->bind_result($patient);
    while ($stmt->fetch()) {
      $patientsArray[] = $patient;
    }
    $stmt->close();
    // for each patientId, fill out a row
    foreach ($patientsArray as $patient) {
      echo <<<EOT
      <h2>Missed Patient Activity</h2>

      <table>
        <thead>
        
      EOT;
    }
  }
}

// NOTE: Move all this onto another branch. Use the first query to get every patientId on a given day and put them in an array
// NOTE: use this query then in that foreach loop to find missing checkboxes for that patientId:
// NOTE: SELECT firstName, lastName, morningMed, afternoonMed, nightMed, breakfast, lunch, dinner FROM users JOIN patients ON users.id = patients.userId JOIN checkboxes ON patients.patientId = checkboxes.patientId WHERE checkboxDate = "2020-12-07" AND checkboxes.patientId = 1 AND ((morningMed = 0) OR (afternoonMed = 0) OR (nightMed = 0) OR (breakfast = 0) OR (lunch = 0) OR (dinner = 0));
?>
