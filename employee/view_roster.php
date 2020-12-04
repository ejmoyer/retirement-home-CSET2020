<?php
$mysqli = new mysqli('localhost', 'root', '', 'retirement');
// if they are not a logged in user, send them to home
session_start();
if (!$_SESSION['user']) {
  header("Location: ../home.html");
  exit;
}
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$date = date("Y-m-d");

// logout button
echo <<<EOT
<form action="../authentication/logout.php" method="get">
<input type=submit value=Logout>
</form>
EOT;

echo <<<EOT
<form action="view_roster.php" method="post">
<input type="date" name="rosterDate" value="$date">

<input type="submit">
</form>
EOT;
// if the user submitted a roster date to check, create the roster table
if (isset($_POST['rosterDate'])) {
  echo <<<EOT
    <table>
    <thead>
      <tr>
        <th scope="col">Supervisor</th>
        <th scope="col">Doctor</th>
        <th scope="col">Caregiver 1</th>
        <th scope="col">Caregiver 2</th>
        <th scope="col">Caregiver 3</th>
        <th scope="col">Caregiver 4</th>
      </tr>
    </thead>
    <tbody>
      <tr>
    EOT;
// because of how the roster is set up, we will set each row to it's own variable and make an array out of it
  if ($stmt = $mysqli->prepare("SELECT * FROM rosters WHERE rosterDate = ?")) {
    $stmt->bind_param("s", $_POST['rosterDate']);
    $stmt->execute();
    $result = $stmt->get_result();
    // fetch values
    while ($row = $result->fetch_assoc()) {
      $supervisorId = $row['supervisorId'];
      $doctorId = $row['doctorId'];
      $caregiverOne = $row['caregiverOne'];
      $caregiverTwo = $row['caregiverTwo'];
      $caregiverThree = $row['caregiverThree'];
      $caregiverFour = $row['caregiverFour'];
    }
    // making it an array will allow us to go through each item and get the employee's names one at a time and then add them to the table
    $employees = array($supervisorId, $doctorId, $caregiverOne, $caregiverTwo, $caregiverThree, $caregiverFour);
    $stmt->close();

    if ($stmt = $mysqli->prepare("SELECT firstName, lastName FROM users INNER JOIN employees ON users.id = employees.userId WHERE employeeId = ?")) {
      foreach ($employees as $employee) {
        $stmt->bind_param('s', $employee);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
        printf(<<<EOT
        <td>%s %s</td>
        EOT, $row['firstName'], $row['lastName']);
      }
      }
      $stmt->close();
    }
      echo <<<EOT
        </tr>
          <td></td>
          <td></td>
          <td>Group 1</td>
          <td>Group 2</td>
          <td>Group 3</td>
          <td>Group 4</td>
        </tr>
        </tbody>
      </table>
      EOT;
  }
}
$mysqli->close();
?>
