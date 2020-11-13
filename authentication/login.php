<?php
// establish DB connection
$link = mysqli_connect("localhost", "root", "", "retirement");
session_start();
if (!$link) { // If DB doesn't connect
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
// see if email AND password were submitted
if (isset($_POST['email']) and isset($_POST['password'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  if ($stmt = mysqli_prepare($link, "SELECT id, roleId FROM users WHERE email=? and password=?)")) {
    // the parameters of the search
    mysqli_stmt_bind_param($stmt, "ss", $email, $password);

    // execute the statement
    mysqli_stmt_execute($stmt);

    // bind the results of the query to variables
    mysqli_stmt_bind_result($stmt, $id, $roleId);

    // if the query went through and found data
    if (mysqli_stmt_fetch($stmt)) {
      $_SESSION['user'] = $id;
      $_SESSSION['user-access'] = $roleId;
    }
    mysqli_stmt_close($stmt);
  }
}

// close DB after
mysqli_close($link);
?>
