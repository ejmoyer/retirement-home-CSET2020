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
if ($_SESSION['access'] > 2) {
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
<input class='logout' type=submit value=Logout>
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

  if ($stmt = $mysqli->prepare("SELECT checkboxes.patientId FROM users JOIN patients ON users.id = patients.userId JOIN checkboxes ON patients.patientId = checkboxes.patientId WHERE checkboxDate = ? AND ((morningMed = 0) OR (afternoonMed = 0) OR (nightMed = 0) OR (breakfast = 0) OR (lunch = 0) OR (dinner = 0));")) {
    $stmt->bind_param("s", $_POST['checkboxDate']);
    $stmt->execute();
    $stmt->bind_result($patient);
    while ($stmt->fetch()) {
      $patientsArray[] = $patient;
    }
    $stmt->close();

    echo <<<EOT
    <h2>Missed Patient Activity</h2>

    <table id='tableStyle'>
      <thead>
        <tr>
          <th scope="col">Patient's Name</th>
          <th scope="col">Doctor's Name</th>
          <th scope="col">Doctor's Appointment</th>
          <th scope="col">Caregiver's Name</th>
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
    // for each patientId, fill out a row
    foreach ($patientsArray as $patient) {
      if ($stmt = $mysqli->prepare("SELECT firstName, lastName FROM patients JOIN users ON users.id = patients.userId WHERE patientId = ?")) {
        $stmt->bind_param("i", $patient);
        $stmt->execute();
        $stmt->bind_result($patFirstName, $patLastName);
        while ($stmt->fetch()) {
          printf (<<<EOT
          <tr scope="row">
          <td>%s %s</td>
          EOT, $patFirstName, $patLastName);
        }
        $stmt->close();

      if ($stmt = $mysqli->prepare("SELECT firstName, lastName FROM users JOIN employees ON employees.userId = users.id JOIN rosters ON employees.employeeId = rosters.doctorId WHERE rosters.rosterDate = ?")) {
        $stmt->bind_param("s", $_POST['checkboxDate']);
        $stmt->execute();
        $stmt->bind_result($docFirstName, $docLastName);
        while ($stmt->fetch()) {
          printf (<<<EOT
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
            echo "</tr>";
          }
        }
      }
    }
  }
  echo <<<EOT
  </tbody>
  </table>
  EOT;
  }
}
?>
