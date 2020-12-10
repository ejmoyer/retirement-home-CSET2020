<?php
echo <<<EOT
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="../static/styles.css" type="text/css" />
  <title>Register</title>
  <script defer src="registerPatientFamilyInfo.js"></script>
</head>
EOT;
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
<input class='logout' type=submit value=Logout>
</form>
EOT;

$currentDate = date("Y-m-d");
$originalTotal = 0;

echo <<<EOT
<h1>Payment</h1>
EOT;

if (isset($_POST['totalDue']) and ($_POST['totalDue']) > 0) {
  if ($stmt = $mysqli->prepare("UPDATE patients SET lastUpdateDate = ?, totalDue = ? WHERE patientId = ?")) {
    $stmt->bind_param("sii", $currentDate, $_POST['totalDue'], $_POST['patientId']);
    $stmt->execute();
    $stmt->close();
  }
} elseif (isset($_POST['newPayment']) and ($_POST['newPayment']) > 0) {
  if ($stmt = $mysqli->prepare("SELECT totalDue FROM patients WHERE patientId = ?")) {
    $stmt->bind_param('i', $_POST['patientId']);
    $stmt->execute();
    $stmt->bind_result($totalDue);
    while ($stmt->fetch()) {
      $originalTotal = $totalDue;
    }
    $stmt->close();
    $newTotal = $originalTotal - $_POST['newPayment'];
    if ($stmt = $mysqli->prepare("UPDATE patients SET totalDue = ? WHERE patientId = ?")) {
      $stmt->bind_param("ii", $newTotal, $_POST['patientId']);
      $stmt->execute();
      $stmt->close();
    }
  }
}
echo <<<EOT
<p>Update successful.</p>
<a href="payment.php">Go back.</a>
EOT;
$mysqli->close();
?>
