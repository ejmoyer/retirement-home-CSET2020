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

  echo <<<EOT
  <h1>Doctor's Home</h1>

  <table>
    <thead>
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Date</th>
        <th scope="col">Comment</th>
        <th scope="col">Morning Medicine</th>
        <th scope="col">Afternoon Medicine</th>
        <th scope="col">Night Medicine</th>
        <th scope="col">More Info</th>
      </tr>
    </thead>
    <tbody>
  EOT;

  // select query for all appointments
  if ($stmt = $mysqli->prepare("SELECT firstName, lastName, appDate, appComment, morningMed, afternoonMed, nightMed, appointments.patientId FROM appointments JOIN patients ON patients.patientId = appointments.patientId JOIN users ON users.id = patients.userId WHERE doctorId = ?")) {
    $stmt->bind_param("i", $doctorId);
    $stmt->execute();
    $stmt->bind_result($patFirstName, $patLastName, $appDate, $comment, $morningMed, $afternoonMed, $nightMed, $patientId);
    while ($stmt->fetch()) {
      printf (<<<EOT
      <tr>
        <td>%s %s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <form action="doc-patient-info.php" method="post">
        <td>
        <input type="text" value="%s" hidden>
        <input type="submit" value="More Info">
        </form>
        </td>
      </tr>

      EOT, $patFirstName, $patLastName, $appDate, $comment, $morningMed, $afternoonMed, $nightMed, $patientId);
    }

  }
}
?>
