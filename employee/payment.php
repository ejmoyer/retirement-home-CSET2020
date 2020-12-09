<?php
session_start();
if ($_SESSION['access'] != 1) {
  header('Location: ../home.html');
  exit;
}

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

echo <<<EOT
<h1>Payment</h1>

<form action="payment.php" method="post">
<label for="patientId">Patient ID</label>
<input type="text" name="patientId">

<input type="submit">
</form>
EOT;
?>
