<?php
session_start();
// If they aren't an admin or supervisor, stop the script and send them to the home page.
if ($_SESSION['access'] > 2) {
  header("Location: ../home.html");
  exit();
}

$mysqli = new mysqli('localhost', 'root', '', 'retirement');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

echo <<<EOT
<h1>Employees</h1>

<form action="employee-list.php" method="post">
<table>
<thead>
  <tr>
    <th scope="col">ID</th>
    <th scope="col">Name</th>
    <th scope="col">Role</th>
    <th scope="col">Salary</th>
  </tr>
</thead>
<tbody>
EOT;

if ($stmt = $mysqli->prepare("SELECT employeeId, firstName, lastName, role, salary FROM users INNER JOIN employees ON users.id = employees.userId INNER JOIN roles ON users.roleId = roles.roleId;")) {
  $stmt->execute();
  $stmt->bind_result($employeeId, $firstName, $lastName, $role, $salary);
  // fetch values
    while ($stmt->fetch()) {
        printf (<<<EOT
        <tr>
          <td>%s</td>
          <td>%s</td>
          <td>%s</td>
          <td>%s</td>
        </tr>
        EOT, $employeeId, $firstName . " " . $lastName, $role, $salary);
  }
  echo <<<EOT
    </tbody>
  </table>
  EOT;
}
?>
