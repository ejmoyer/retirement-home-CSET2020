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
// if the person on this page is not a supervisor or admin, send them to the home page.
if ($_SESSION['access'] > 2) {
  header("Location: ../home.html");
  exit;
}
$mysqli = new mysqli('localhost', 'root', '', 'retirement');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$date = date("Y-m-d");

// logout button
echo <<<EOT
<form action="../authentication/logout.php" method="get">
<input class='logout' type=submit value=Logout>
</form>
EOT;

// start of the page
echo(<<<EOT
<h1>Create Roster</h1>
<form action="create_roster.php" method="post">
<div class='roster'>
<label for="roster-date">Date:</label>
<input type="date" name="roster-date" value="$date">

<label for="supervisor">Supervisor:</label>
<select name="supervisor">
EOT);
// supervisor query
$stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM employees INNER JOIN users ON employees.userId = users.id WHERE roleId = 2;");
$stmt->execute();
// get the results of the query and create options for those names
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  printf (<<<EOT
  <option value="%s">%s %s</option>
  EOT, $row['employeeId'], $row['firstName'], $row['lastName']);
}
echo(<<<EOT
</select>

<label for="doctor">Doctor:</label>
<select name="doctor">
EOT);
$stmt->close();

// doctor query
$stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM users INNER JOIN employees ON users.id = employees.userId WHERE roleId = 3;");
$stmt->execute();
// get the results of the query and create options for those names
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  printf (<<<EOT
  <option value="%s">%s %s</option>
  EOT, $row['employeeId'], $row['firstName'], $row['lastName']);
}
echo(<<<EOT
</select>

<label for="caregiverOne">Caregiver 1:</label>
<select name="caregiverOne">
EOT);
$stmt->close();

// caregivers query
$stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM users INNER JOIN employees ON users.id = employees.userId WHERE roleId = 4;");
$stmt->execute();
// get the results of the query and create options for those names
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  printf (<<<EOT
  <option value="%s">%s %s</option>
  EOT, $row['employeeId'], $row['firstName'], $row['lastName']);
}
echo(<<<EOT
</select>

<label for="caregiverTwo">Caregiver 2:</label>
<select name="caregiverTwo">
EOT);

$stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM users INNER JOIN employees ON users.id = employees.userId WHERE roleId = 4;");
$stmt->execute();
// get the results of the query and create options for those names
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  printf (<<<EOT
  <option value="%s">%s %s</option>
  EOT, $row['employeeId'], $row['firstName'], $row['lastName']);
}
echo(<<<EOT
</select>

<label for="caregiverThree">Caregiver 3:</label>
<select name="caregiverThree">
EOT);
$stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM users INNER JOIN employees ON users.id = employees.userId WHERE roleId = 4;");
$stmt->execute();
// get the results of the query and create options for those names
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  printf (<<<EOT
  <option value="%s">%s %s</option>
  EOT, $row['employeeId'], $row['firstName'], $row['lastName']);
}
echo(<<<EOT
</select>

<label for="caregiverFour">Caregiver 4:</label>
<select name="caregiverFour">
</div>
EOT);
$stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM users INNER JOIN employees ON users.id = employees.userId WHERE roleId = 4;");
$stmt->execute();
// get the results of the query and create options for those names
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  printf (<<<EOT
  <option value="%s">%s %s</option>
  EOT, $row['employeeId'], $row['firstName'], $row['lastName']);
}
echo(<<<EOT
</select>

<input type="submit">
</form>

EOT);
$stmt->close();
// if the page is posted
$groupOne = 1;
$groupTwo = 2;
$groupThree = 3;
$groupFour = 4;
if ((isset($_POST['roster-date'])) &&
    (isset($_POST['supervisor'])) &&
    (isset($_POST['doctor'])) &&
    (isset($_POST['caregiverOne'])) &&
    (isset($_POST['caregiverTwo'])) &&
    (isset($_POST['caregiverThree'])) &&
    (isset($_POST['caregiverFour']))) {
      if ($stmt = $mysqli->prepare("INSERT INTO rosters (rosterDate, supervisorId, doctorId, caregiverOne, caregiverTwo, caregiverThree, caregiverFour) VALUES (?, ?, ?, ?, ?, ?, ?);")) {
        $stmt->bind_param("sssssss", $_POST['roster-date'], $_POST['supervisor'], $_POST['doctor'], $_POST['caregiverOne'], $_POST['caregiverTwo'], $_POST['caregiverThree'], $_POST['caregiverFour']);
        $stmt->execute();
        $stmt->close();

        $onePatients = [];
        $twoPatients = [];
        $threePatients = [];
        $fourPatients = [];

        if ($stmt = $mysqli->prepare("SELECT patientId FROM patients WHERE groupId = ?")) {
          $stmt->bind_param('i', $groupOne);
          $stmt->execute();
          $result = $stmt->get_result();

          while ($row = $result->fetch_assoc()) {
            $onePatients[] = $row['patientId'];
          }

          $stmt->bind_param('i', $groupTwo);
          $stmt->execute();
          $result = $stmt->get_result();

          while ($row = $result->fetch_assoc()) {
            $twoPatients[] = $row['patientId'];
          }

          $stmt->bind_param('i', $groupThree);
          $stmt->execute();
          $result = $stmt->get_result();

          while ($row = $result->fetch_assoc()) {
            $threePatients[] = $row['patientId'];
          }

          $stmt->bind_param('i', $groupFour);
          $stmt->execute();
          $result = $stmt->get_result();

          while ($row = $result->fetch_assoc()) {
            $fourPatients[] = $row['patientId'];
          }
          $stmt->close();

          if ($stmt = $mysqli->prepare("INSERT INTO checkboxes (checkboxDate, patientId, caregiverId) VALUES (?, ?, ?)")) {
            foreach ($onePatients as $patient) {
              $stmt->bind_param('sii', $_POST['roster-date'], $patient, $_POST['caregiverOne']);
              $stmt->execute();
            }

            foreach ($twoPatients as $patient) {
              $stmt->bind_param('sii', $_POST['roster-date'], $patient, $_POST['caregiverTwo']);
              $stmt->execute();
            }

            foreach ($threePatients as $patient) {
              $stmt->bind_param('sii', $_POST['roster-date'], $patient, $_POST['caregiverThree']);
              $stmt->execute();
            }

            foreach ($fourPatients as $patient) {
              $stmt->bind_param('sii', $_POST['roster-date'], $patient, $_POST['caregiverFour']);
              $stmt->execute();
            }
            $stmt->close();
            if ($_SESSION['access'] == 1) {
              header("Location: admin-home.php");
              exit;
            } elseif ($_SESSION['access'] == 2) {
              header("Location: supervisor-home.php");
              exit;
            }
          }
        }
        }
      }
$mysqli->close();
?>
