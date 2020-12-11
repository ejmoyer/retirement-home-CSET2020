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
<input class='logout' type=submit value=Logout>
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
  <h1>Patient of Doctor</h1>

  <table id='tableStyle'>
    <thead>
      <tr>
        <th scope="col">Date</th>
        <th scope="col">Comment</th>
        <th scope="col">Morning Medicine</th>
        <th scope="col">Afternoon Medicine</th>
        <th scope="col">Night Medicine</th>
      </tr>
    </thead>
    <tbody>
  EOT;

  if ($stmt = $mysqli->prepare("SELECT appDate, appComment, morningMed, afternoonMed, nightMed FROM appointments WHERE doctorId = ? and patientId = ? AND ((morningMed IS NOT NULL) OR (afternoonMed IS NOT NULL) OR (nightMed IS NOT NULL));")) {
    $stmt->bind_param("ii", $doctorId, $_POST['patientId']);
    $stmt->execute();
    $stmt->bind_result($appDate, $appComment, $morningMed, $afternoonMed, $nightMed);
    while ($stmt->fetch()) {
      printf (<<<EOT
      <tr>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
        <td>%s</td>
      </tr>
      EOT, $appDate, $appComment, $morningMed, $afternoonMed, $nightMed);
    }
    $stmt->close();
    echo <<<EOT
    </tbody>
    </table>

    <h2>New Prescription</h2>

    <form action="doc-patient-info.php" method="post">
    <input type="text" name="patientId" value="$_POST[patientId]" hidden>
    <label for="comment">Comment</label>
    <input type="text" name="comment">

    <label for="morningMed">Morning Med</label>
    <input type="text" name="morningMed">

    <label for="afternoonMed">Afternoon Med</label>
    <input type="text" name="afternoonMed">

    <label for="nightMed">Night Med</label>
    <input type="text" name="nightMed">
    EOT;
    if ($stmt = $mysqli->prepare("SELECT * FROM appointments WHERE patientId = ? and doctorId = ? and appDate = ?")) {
      $stmt->bind_param("iis", $_POST['patientId'], $doctorId, $currentDate);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        echo <<<EOT
        <input type="submit" value="Ok">
        <input type="reset" value="Cancel">
        </form>
        EOT;
      } else {
        echo <<<EOT
        <input type="submit" value="Ok" disabled>
        <input type="reset" value="Cancel" disabled>
        </form>
        EOT;
      }
      $stmt->close();
      if (isset($_POST['comment']) && (isset($_POST['morningMed'])) && (isset($_POST['afternoonMed'])) && (isset($_POST['nightMed']))) {
        if ($stmt = $mysqli->prepare("UPDATE appointments SET appComment = ?, morningMed = ?, afternoonMed = ?, nightMed = ? WHERE patientId = ? AND doctorId = ? AND appDate = ?")) {
          $stmt->bind_param("ssssiis", $_POST['comment'], $_POST['morningMed'], $_POST['afternoonMed'], $_POST['nightMed'], $_POST['patientId'], $doctorId, $currentDate);
          $stmt->execute();
          $stmt->close();
          header("Location: doctor-home.php");
          exit;
        }
      }
    }
  }
}
$mysqli->close();
?>
