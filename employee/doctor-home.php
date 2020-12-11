<?php
echo <<<EOT
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../static/styles.css" type="text/css" />
  <title>Register</title>
  <script defer src="registerPatientFamilyInfo.js"></script>
</head>
EOT;
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
        <td>
        <form action="doc-patient-info.php" method="post">
        <input type="text" name="patientId" value="%s" hidden>
        <input type="submit" value="More Info">
        </form>
        </td>
      </tr>
      EOT, $patFirstName, $patLastName, $appDate, $comment, $morningMed, $afternoonMed, $nightMed, $patientId);
    }
    $stmt->close();
    echo <<<EOT
    </tbody>
    </table>

    <h1>Appointments</h1>

    <form action="doctor-home.php" method="post">
    <input type="date" name="untilDate">
    <input type="submit">
    </form>

    <table>
      <thead>
        <tr>
          <th scope="col">Patient Name</th>
          <th scope="col">Date</th>
        </tr>
      </thead>
      <tbody>
    EOT;
    if (isset($_POST['untilDate'])) {
      if ($stmt = $mysqli->prepare("SELECT firstName, lastName, appDate FROM appointments JOIN patients ON patients.patientId = appointments.patientId JOIN users ON patients.userId = users.id WHERE appointments.doctorId = ? AND appointments.appDate BETWEEN CAST(? AS DATE) AND CAST(? AS DATE)")) {
        $stmt->bind_param("iss", $doctorId, $currentDate, $_POST['untilDate']);
        $stmt->execute();
        $stmt->bind_result($patFirstName, $patLastName, $appDate);
        while ($stmt->fetch()) {
          printf (<<<EOT
          <tr>
            <td>%s %s</td>
            <td>%s</td>
          </tr>
          EOT, $patFirstName, $patLastName, $appDate);
        }
        $stmt->close();
        echo "</tbody>";
        echo "</table>";
      }
    }
  }
}
$mysqli->close();
?>
