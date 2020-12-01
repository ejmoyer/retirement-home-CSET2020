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
// table + headings
echo <<<EOT
<h1>Employees</h1>

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

// query for all employees
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
  // create the rest of the page
  echo <<<EOT
    </tbody>
  </table>

  <form action="employee-list.php" method="post">
  <label for="empId">Employee ID:</label>
  <input type="text" name="empId">
  EOT;

  // only admins can change salary values, supervisors can't.
  if ($_SESSION['access'] == 1) {
    echo <<<EOT
    <label for="salary">Salary:</label>
    <input type="text" name="salary">

    <input type="submit">
    <input type="reset" value="Cancel">
    </form>
    EOT;
  } else {
    echo <<<EOT
    <label for="salary">Salary:</label>
    <input type="text" name="salary" disabled>

    <input type="submit" value="Ok">
    <input type="reset" value="Cancel">
    </form>
    EOT;
  }
  $stmt->close();

  // if the page was posted to
  if (isset($_POST['empId']) && isset($_POST['salary'])) {
    if ($stmt = $mysqli->prepare("UPDATE employees SET salary = ? WHERE employeeId = ?;")) {
      $stmt->bind_param('ss', $_POST['salary'], $_POST['empId']);
      $stmt->execute();
      $stmt->close();
      // reload the page afterward (sends a get so things are posted twice)
      header('Location: employee-list.php');
    }
  }
}

$mysqli->close();
?>
