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
// If they aren't an admin, stop the script.
if ($_SESSION['access'] > 1) {
  header("Location: ../home.html");
  exit;
}

$mysqli = new mysqli('localhost', 'root', '', 'retirement');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
/* Start of Insert new Role */
if (isset($_POST['newRole']) and isset($_POST['access'])) {
  $newRole = $_POST["newRole"];
  $access = $_POST["access"];

  $stmt = $mysqli->prepare("INSERT INTO roles (role, accessLevel) VALUES (?, ?)");
  $stmt->bind_param("ss", $newRole, $access);
  $stmt->execute();
  $stmt->close();
}
/* End of Insert New Role */

// logout button
echo <<<EOT
<form action="../authentication/logout.php" method="get">
<input class='logout'type=submit value=Logout>
</form>
EOT;

/* Start of Select All Roles */
// query the database for all current roles
// prepare the statement
$stmt = $mysqli->prepare("SELECT role, accessLevel FROM roles");
$stmt->execute();
$stmt->bind_result($role, $accessLevel);
echo <<<EOT
<h1>Roles</h1>

<div class='inputs'>
<form action="rolePage.php" method="post">
<label for="newRole">New Role</label>
<input type="text" name="newRole">

<label for="access">Access Level</label>
<input type="text" name="access">

<input type="submit">
</form>
</div>

<table id='tableStyle'>
  <thead>
    <tr>
      <th scope="col">Role</th>
      <th scope="col">Access Level</th>
    </tr>
  </thead>
  <tbody>
EOT;
// fetch values
  while ($stmt->fetch()) {
      printf (<<<EOT
      <tr>
        <td>%s</td>
        <td>%s</td>
      </tr>
      EOT, $role, $accessLevel);
}
echo <<<EOT
  </tbody>
</table>
EOT;
// close the statement
$stmt->close();
/* End of Select All Roles */
$mysqli->close();
?>
