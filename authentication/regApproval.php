<?php
// start the session and make sure they're a supervisor or admin
session_start();

if ($_SESSION['access'] > 2) {
  header("Location: ../home.html");
  exit;
}

// logout button
echo <<<EOT
<form action="logout.php" method="get">
<input type=submit value=Logout>
</form>
EOT;

$mysqli = new mysqli("localhost", "root", "", "retirement");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
// start the html to select the people to approve
  echo <<<EOT
  <form action="regApproval.php" method="post">
    <table>
    <thead>
      <tr>
        <th scope="col">Yes</th>
        <th scope="col">No</th>
        <th scope="col">Name</th>
        <th scope="col">Role</th>
      </tr>
    </thead>
    <tbody>
  EOT;
  // prepare a join to get all the info we need
  if ($stmt = $mysqli->prepare("SELECT id, firstName, lastName, role FROM users INNER JOIN roles ON users.roleId=roles.roleId WHERE users.approved = 0")) {
    // execute the statement
    $stmt->execute();
    // get the result of the query
    $result = $stmt->get_result();
    // get each row of the query
    while ($row = $result->fetch_assoc()) {
    printf (<<<EOT
    <tr>
      <td><input type="checkbox" name=yeschecklist[] value=%s></td>
      <td><input type="checkbox" name=nochecklist[] value=%s></td>
      <td>%s</td>
      <td>%s</td>
    </tr>
    EOT, $row['id'], $row['id'], $row['firstName'] . " " . $row['lastName'], $row['role']);
  }
} // complete the table
    echo <<<EOT
      </tbody>
    </table>
    <input type="submit">
    </form>
    EOT;
    // get each checkbox which each have a user id on it
    if (!empty($_POST['yeschecklist'])) {
      foreach($_POST['yeschecklist'] as $selected) {
        if ($stmt = $mysqli->prepare("UPDATE users SET approved = 1 WHERE id = ?")) {
          $stmt->bind_param('i', $selected);
          $stmt->execute();
          $stmt->close();
        }
      }
    }
    // get each checkbox that said no
    if (!empty($_POST['nochecklist'])) {
      foreach($_POST['nochecklist'] as $selected) {
        if ($stmt = $mysqli->prepare("DELETE FROM users WHERE id=?")) {
          $stmt->bind_param('i', $selected);
          $stmt->execute();
          $stmt->close();
        }
      }
      // redirect to the page again to look for any new approvals needed
      header('Location: regApproval.php');
    }

$mysqli->close();
?>
