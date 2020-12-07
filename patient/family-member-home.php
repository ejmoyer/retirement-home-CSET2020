<?php
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
<input type=submit value=Logout>
</form>
EOT;

echo <<<EOT
<form action="family-member-home.php" method="post">
<label for="rosterDate">Date</label>
<input type="text" name="rosterDate">

<label for="familyCode">Family Code</label>
<input type="text" name="familyCode">

<label for="patientId">Patient ID</label>
<input type="text" name="patientId">
EOT;

if (isset($_POST['rosterDate']) && isset($_POST['familyCode']) && isset($_POST['patientId'])) {
  echo <<<EOT
  <table>
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
}
$mysqli->close();
?>
