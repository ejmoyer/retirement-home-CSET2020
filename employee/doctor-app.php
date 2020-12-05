<?php
session_start();

// if they are not a supervisor or admin
if ($_SESSION['access'] > 2) {
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

echo <<<EOT
<form action="doctor-app.php" method="post">
<label for="patientId">Patient ID:</label>
<input type="text" name="patientId">

<input type="submit">
</form>
EOT;

if (isset($_POST['patientId'])) {
  // get patient name
  if ($stmt = $mysqli->prepare("SELECT firstName, lastName FROM users JOIN patients ON users.id = patients.userId WHERE patientId = ?")) {
    $stmt->bind_param('i', $_POST['patientId']);
    $stmt->execute();
    $stmt->bind_result($firstName, $lastName);

    while ($stmt->fetch()) {
      printf (<<<EOT
      <form action="doctor-app.php" method="post">
      <input type="text" name="patientId" value="%s" hidden>

      <label for="patientName">Patient Name</label>
      <input type="text" name="patientName" value="%s %s" disabled>

      <label for="rosterDate">Roster Date</label>
      <input type="date" name="rosterDate">

      <input type="submit">
      </form>
      EOT, $_POST['patientId'], $firstName, $lastName);
    }
    $stmt->close();

    // get the doctor for that date
    if (isset($_POST["rosterDate"])) {
      if ($stmt = $mysqli->prepare("SELECT doctorId, firstName, lastName FROM users JOIN employees ON users.id = employees.userId JOIN rosters ON employees.employeeId = rosters.doctorId WHERE rosters.rosterDate = ?")) {
        $stmt->bind_param("s", $_POST['rosterDate']);
        $stmt->execute();
        $stmt->bind_result($doctorId, $firstName, $lastName);
        while ($stmt->fetch()) {
          printf (<<<EOT
          <form action="doctor-app.php" method="post">
          <input type="text" name="patientId" value="$_POST[patientId]" hidden>
          <input type="text" name="rosterDate" value="$_POST[rosterDate]" hidden>
          <select name="doctorId">
          <option value="%s">%s %s</option>
          </select>

          <input type="submit">
          </form>
          EOT, $doctorId, $firstName, $lastName);
        }
        $stmt->close();

        if (isset($_POST['doctorId'])) {
          // insert into appointments
          if ($stmt = $mysqli->prepare("INSERT INTO appointments (doctorId, patientId, appDate) VALUES (?, ?, ?)")) {
            $stmt->bind_param("iis", $_POST['doctorId'], $_POST['patientId'], $_POST['rosterDate']);
            $stmt->execute();
            $stmt->close();
            header("Location: doctor-app.php");
          }
        }
      }
    }
  }
}
$mysqli->close();
?>
