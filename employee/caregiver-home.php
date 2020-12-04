<?php
session_start();

$mysqli = new mysqli('localhost', 'root', '', 'retirement');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
// check access
if ($_SESSION['access'] != 4) {
  header('Location: ../home.html');
  exit;
}

// logout button
echo <<<EOT
<form action="../authentication/logout.php" method="get">
<input type=submit value=Logout>
</form>
EOT;

// get the caregiver's employee id
if ($stmt = $mysqli->prepare("SELECT employeeId FROM employees WHERE userId = ?")) {
  $stmt->bind_param('s', $_SESSION['user']);
  $stmt->execute();
  $stmt->bind_result($caregiver);
  while ($stmt->fetch()) {
    $caregiverId = $caregiver;
  }
  $stmt->close();
}
// initialize the page.
echo <<<EOT
<h1>Caregiver's Home</h1>

<table>
  <thead>
    <tr>
      <th scope="col">Name</th>
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
// get today's date to check what checkboxes to load
$date = date("Y-m-d");

// NOTE: This page got VERY confusing VERY fast but I will try my best to comment it and explain it.
// NOTE: This query is very long and drawn out because it will create all the possible patients rows.
if ($stmt = $mysqli->prepare("SELECT checkboxes.patientId, firstName, lastName, morningMed, afternoonMed, nightMed, breakfast, lunch, dinner FROM checkboxes JOIN patients ON patients.patientId = checkboxes.patientId JOIN users ON patients.userId = users.id WHERE caregiverId = ? AND checkboxDate = ?")) {
  $stmt->bind_param("is", $caregiverId, $date);
  $stmt->execute();
  $stmt->bind_result($patientId, $firstName, $lastName, $morningMed, $afternoonMed, $nightMed, $breakfast, $lunch, $dinner);
  while ($stmt->fetch()) {
    printf(<<<EOT
    <form action="caregiver-home.php" method="post">
    <tr scope="row">
    <td><input name="patientId" type="text" value="%s" hidden>%s %s</td>
    EOT, $patientId, $firstName, $lastName);
    // NOTE: This is where it gets confusing. I made a foreach loop and arrayed up all the results. This is so it can compare values to the database. Disabled checkboxes are not posted.
    foreach (array("morningMed"=>$morningMed, "afternoonMed"=>$afternoonMed, "nightMed"=>$nightMed, "breakfast"=>$breakfast, "lunch"=>$lunch, "dinner"=>$dinner) as $column => $value) {
      if ($$column == 0) {
      echo "<td><input type=checkbox name=$column value=1></td>";
      // if the column's result variable is equal to 0, allow it to be checked.
    } else {
      echo "<td><input type=checkbox name=$column checked disabled></td>";
      // else, don't let it be checked and disable it so it is not posted
    }
    }
    // there will be a submit button on each row because otherwise they're all named the same thing.
  echo <<<EOT
    <td><input type="submit"></td>
    <td><input type="reset"></td>
    </tr>
    </form>
    EOT;
    }
  }
  $stmt->close();
echo <<<EOT
</tbody>
</table>
EOT;
// NOTE: These have to be done separately because we do not know how many boxes the caregiver will check each time.
// These will always redirect to caregiver home so the caregiver can submit other patient's checkboxes.
if (isset($_POST['morningMed'])) {
if ($stmt = $mysqli->prepare("UPDATE checkboxes SET morningMed = ? WHERE patientId = ? AND checkboxDate = ?")) {
  $stmt->bind_param('sss', $_POST['morningMed'], $_POST['patientId'], $date);
  $stmt->execute();

  $stmt->close();
  header('Location: caregiver-home.php');
  }
}

if (isset($_POST['afternoonMed'])) {
if ($stmt = $mysqli->prepare("UPDATE checkboxes SET afternoonMed = ? WHERE patientId = ? AND checkboxDate = ?")) {
  $stmt->bind_param('sss', $_POST['afternoonMed'], $_POST['patientId'], $date);
  $stmt->execute();

  $stmt->close();
  header('Location: caregiver-home.php');
  }
}

if (isset($_POST['nightMed'])) {
if ($stmt = $mysqli->prepare("UPDATE checkboxes SET nightMed = ? WHERE patientId = ? AND checkboxDate = ?")) {
  $stmt->bind_param('sss', $_POST['nightMed'], $_POST['patientId'], $date);
  $stmt->execute();

  $stmt->close();
  header('Location: caregiver-home.php');
  }
}

if (isset($_POST['breakfast'])) {
if ($stmt = $mysqli->prepare("UPDATE checkboxes SET breakfast = ? WHERE patientId = ? AND checkboxDate = ?")) {
  $stmt->bind_param('sss', $_POST['breakfast'], $_POST['patientId'], $date);
  $stmt->execute();

  $stmt->close();
  header('Location: caregiver-home.php');
  }
}

if (isset($_POST['lunch'])) {
if ($stmt = $mysqli->prepare("UPDATE checkboxes SET lunch = ? WHERE patientId = ? AND checkboxDate = ?")) {
  $stmt->bind_param('sss', $_POST['lunch'], $_POST['patientId'], $date);
  $stmt->execute();

  $stmt->close();
  header('Location: caregiver-home.php');
  }
}

if (isset($_POST['dinner'])) {
if ($stmt = $mysqli->prepare("UPDATE checkboxes SET dinner = ? WHERE patientId = ? AND checkboxDate = ?")) {
  $stmt->bind_param('sss', $_POST['dinner'], $_POST['patientId'], $date);
  $stmt->execute();

  $stmt->close();
  header('Location: caregiver-home.php');
  }
}
$mysqli->close();
?>
