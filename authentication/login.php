<?php
// establish DB connection
$mysqli = new mysqli("localhost", "root", "", "retirement");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
session_start();

// see if email AND password were submitted
if (isset($_POST['email']) and isset($_POST['password'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Prepare the query
  $stmt = $mysqli->prepare("SELECT id, roleId FROM users WHERE email = ? AND password = ?");
  // bind parameters for query
  $stmt->bind_param("ss", $email, $password);
  // execute the query
  $stmt->execute();
  // create variables to have info bound to them
  $stmt->bind_result($id, $roleId);
  // while you have the data, bind them to the session variable
  while ($stmt->fetch()) {
    printf ("%s, %s", $id, $roleId);
    $_SESSION['user'] = $id;
    $_SESSION['access'] = $roleId;
  }
}
// close the statement
$stmt->close();
// close DB after
$mysqli->close();
// redirect to homepage
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

switch ($_SESSION['access']) {
  case 1:
    $extra = "../employee/admin-home.php";
    header("Location: http://$host$uri/$extra");
    break;
  case 2:
    $extra = "../employee/supervisor-home.php";
    header("Location: http://$host$uri/$extra");
    break;
  case 3:
    $extra = "../employee/doctor-home.php";
    header("Location: http://$host$uri/$extra");
    break;
  case 4:
    $extra = "../employee/caregiver-home.php";
    header("Location: http://$host$uri/$extra");
    break;
  case 5:
    $extra = "../employee/patient-home.php";
    header("Location: http://$host$uri/$extra");
    break;
  case 6:
    $extra = "../patient/family-member-home.php";
    header("Location: http://$host$uri/$extra");
    break;
  }
exit;
?>
