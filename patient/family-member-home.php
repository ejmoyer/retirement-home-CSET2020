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
?>
