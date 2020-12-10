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
if ($_SESSION['access'] != 6) {
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

echo <<<EOT
<h1>Family Member's Home</h1>

<form action="family-member-home.php" method="post">
<label for="rosterDate">Date</label>
<input type="date" name="rosterDate">

<label for="familyCode">Family Code</label>
<input type="text" name="familyCode">

<label for="patientId">Patient ID</label>
<input type="text" name="patientId">

<input type="submit">
EOT;

if (isset($_POST['rosterDate']) && isset($_POST['familyCode']) && isset($_POST['patientId'])) {
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
      $stmt->bind_param("s", $_POST['rosterDate']);
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
      if ($stmt = $mysqli->prepare("SELECT * FROM appointments WHERE appDate = ? and patientId = ?;")) {
        $stmt->bind_param("si", $_POST['rosterDate'], $_POST['patientId']);
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
          $stmt->bind_param("si", $_POST['rosterDate'], $_POST['patientId']);
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
$mysqli->close();
?>
