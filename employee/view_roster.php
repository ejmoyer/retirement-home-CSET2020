<?php
$mysqli = new mysqli('localhost', 'root', '', 'retirement');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
session_start();
$date = date("Y-m-d");

echo <<<EOT
<form action="view_roster.php" method="post">
<input type="date" name="rosterDate" value="$date">

<input type="submit">
</form>
EOT;

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
    EOT;

  if ($stmt = $mysqli->prepare("SELECT * FROM rosters WHERE rosterDate = ?")) {
    $stmt->bind_param("s", $_POST['rosterDate']);
    $stmt->execute();
    $result = $stmt->get_result();
    // fetch values
    while ($row = $result->fetch_assoc()) {
        printf (<<<EOT
        <tr>
          <td>%s</td>
          <td>%s</td>
          <td>%s</td>
          <td>%s</td>
          <td>%s</td>
          <td>%s</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td>Group 1</td>
          <td>Group 2</td>
          <td>Group 3</td>
          <td>Group 4</td>
        </tr>
        EOT, $row['supervisor'], $row['doctor'], $row['caregiverOne'], $row['caregiverTwo'], $row['caregiverThree'], $row['caregiverFour']);
      }
      $stmt->close();
      echo <<<EOT
        </tbody>
      </table>
      EOT;
  }
}
$mysqli->close();
?>
