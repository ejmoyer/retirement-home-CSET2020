<?php
session_start();
// If they aren't an admin, stop the script.
if ($_SESSION['access'] > 1) {
  echo "<p>You shouldn't be here.</p>";
  exit();
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

/* Start of Select All Roles */
// query the database for all current roles
// prepare the statement
$stmt = $mysqli->prepare("SELECT role, accessLevel FROM roles");
$stmt->execute();
$stmt->bind_result($role, $accessLevel);
echo <<<EOT
<table>
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

// create two textboxes that you can type into (new role and access level)
// and a submit button */
echo <<<EOT
<form action="rolePage.php" method="post">
<label for="newRole">New Role</label>
<input type="text" name="newRole">

<label for="access">Access Level</label>
<input type="text" name="access">

<input type="submit">
</form>
EOT;
$mysqli->close();
?>
