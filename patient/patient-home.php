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
<input class='logout' type=submit value=Logout>
</form>
EOT;
$patient = 0;
if ($stmt = $mysqli->prepare("SELECT patientId, firstName, lastName FROM patients JOIN users ON patients.userId = users.id WHERE users.id = ?")) {
  $stmt->bind_param("i", $_SESSION['user']);
  $stmt->execute();
  $stmt->bind_result($patientId, $firstName, $lastName);
  while ($stmt->fetch()) {
  printf (<<<EOT
  <h1>Patient's Home</h1>
  <div class='inputs'>
  <label for="patientId">Patient ID</label>
  <input type="text" name="patientId" value="%s" disabled>

  <label for="patientName">Patient Name</label>
  <input type="text" name="patientName" value="%s %s" disabled>

  <form action="patient-home.php" method="post">
  <label for="checkboxDate">Date</label>
  <input type="date" name="checkboxDate">

  <input type="submit">
  </div>
  </form>
  EOT, $patientId, $firstName, $lastName);
  }
  $patient = $patientId;
  $stmt->close();

  if (isset($_POST['checkboxDate'])) {
    echo <<<EOT
      <table id='tableStyle'>
      <thead>
        <tr>
          <th scope="col">Doctor's Name</th>
          <th scope="col">Doctor's Appointment</th>
          <th scope="col">Caregiver Name</th>
          <th scope="col">Morning Medicine</th>
          <th scope="col">Afternoon Medicine</th>
          <th scope="col">Night Medicine</th>
          <th scope="col">Breakfast</th>
          <th scope="col">Lunch</th>
          <th scope="col">Dinner</th>
        </tr>
      </thead>
      <tbody>
      EOT;
      // query to get the doctor name
      if ($stmt = $mysqli->prepare("SELECT firstName, lastName FROM users JOIN employees ON employees.userId = users.id JOIN rosters ON employees.employeeId = rosters.doctorId WHERE rosters.rosterDate = ?")) {
        $stmt->bind_param("s", $_POST['checkboxDate']);
        $stmt->execute();
        $stmt->bind_result($docFirstName, $docLastName);
        while ($stmt->fetch()) {
          printf (<<<EOT
            <tr scope="row">
            <td>%s %s</td>
            EOT, $docFirstName, $docLastName);
        }
        $stmt->close();
        // check if an appointment exists first
        if ($stmt = $mysqli->prepare("SELECT * FROM appointments JOIN patients ON patients.patientId = appointments.patientId JOIN users ON users.id = patients.userId WHERE appDate = ? and patients.patientId = ?;")) {
          $stmt->bind_param("si", $_POST['checkboxDate'], $patient);
          $stmt->execute();

          $stmt->store_result();
          // if there isn't an appointment, the checkbox will be blank. Otherwise it will be checked.
          if ($stmt->num_rows() == 0) {
            echo ("<td><input type=checkbox disabled></td>");
          } else {
            echo ("<td><input type=checkbox checked disabled></td>");
          }
          $stmt->close();
          // query to get the rest of the table (caregiver and all the checkboxes)
          if ($stmt = $mysqli->prepare("SELECT firstName, lastName, morningMed, afternoonMed, nightMed, breakfast, lunch, dinner FROM users JOIN employees ON employees.userId = users.id JOIN checkboxes ON employees.employeeId = checkboxes.caregiverId WHERE checkboxDate = ? and checkboxes.patientId = ?;")) {
            $stmt->bind_param("si", $_POST['checkboxDate'], $patient);
            $stmt->execute();
            $stmt->bind_result($careFirstName, $careLastName, $morningMed, $afternoonMed, $nightMed, $breakfast, $lunch, $dinner);
            while ($stmt->fetch()) {
              printf (<<<EOT
              <td>%s %s</td>
              EOT, $careFirstName, $careLastName);

              if ($morningMed == 1) {
                echo "<td><input type=checkbox checked disabled></td>";
              } else {
                echo "<td><input type=checkbox disabled></td>";
              }

              if ($afternoonMed == 1) {
                echo "<td><input type=checkbox checked disabled></td>";
              } else {
                echo "<td><input type=checkbox disabled></td>";
              }

              if ($nightMed == 1) {
                echo "<td><input type=checkbox checked disabled></td>";
              } else {
                echo "<td><input type=checkbox disabled></td>";
              }

              if ($breakfast == 1) {
                echo "<td><input type=checkbox checked disabled></td>";
              } else {
                echo "<td><input type=checkbox disabled></td>";
              }

              if ($lunch == 1) {
                echo "<td><input type=checkbox checked disabled></td>";
              } else {
                echo "<td><input type=checkbox disabled></td>";
              }

              if ($dinner == 1) {
                echo "<td><input type=checkbox checked disabled></td>";
              } else {
                echo "<td><input type=checkbox disabled></td>";
              }
            }
            $stmt->close();

            echo <<<EOT
            </tr>
            </tbody>
            </table>
            EOT;
          }
        }
      }
    }
  }
$mysqli->close();
?>
