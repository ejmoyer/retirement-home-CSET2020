<?php
$mysqli = new mysqli('localhost', 'root', '', 'retirement');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
session_start();
$date = date("Y-m-d");
// start of the page
echo(<<<EOT
<form action="create_roster.php" method="post">
<label for="roster-date">Date:</label>
<input type="date" name="roster-date" value="$date">

<label for="supervisor">Supervisor:</label>
<select name="supervisor">
EOT);
// supervisor query
$stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM users INNER JOIN employees ON users.id = employees.userId WHERE roleId = 2;");
$stmt->execute();
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
EOT);
$stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName FROM users INNER JOIN employees ON users.id = employees.userId WHERE roleId = 4;");
$stmt->execute();
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
      }
    }
$mysqli->close();
?>
