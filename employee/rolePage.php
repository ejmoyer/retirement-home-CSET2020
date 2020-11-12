<?php
/* link to database */
$link = mysqli_connect("localhost", "root", "", "retirement");
/* Start of Insert new Role */
if (isset($_POST['newRole']) and isset($_POST['access'])) {
  $newRole = $_POST["newRole"];
  $access = $_POST["access"];

  if ($stmt = mysqli_prepare($link, "INSERT INTO roles (role, accessLevel) VALUES (?, ?)")) {
    mysqli_stmt_bind_param($stmt, "ss", $newRole, $access);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}
/* End of Insert New Role */

/* Start of Select All Roles */
// query the database for all current roles
$query = "SELECT role, accessLevel FROM roles";
// prepare the statement
if ($stmt = mysqli_prepare($link, $query)) {
  // execute statement
  mysqli_stmt_execute($stmt);

  // bind result variable
  mysqli_stmt_bind_result($stmt, $role, $accessLevel);
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
  while (mysqli_stmt_fetch($stmt)) {
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
  mysqli_stmt_close($stmt);
}
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
mysqli_close($link);
?>
