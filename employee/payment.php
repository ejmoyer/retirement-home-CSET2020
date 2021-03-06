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

echo <<<EOT
<h1>Payment</h1>
EOT;

if (!isset($_POST['patientId'])) {
  echo <<<EOT
  <div class='inputs'>
  <form action="payment.php" method="post">
  <label for="patientId">Patient ID</label>
  <input type="text" name="patientId">

  <input type="submit">
  </form>
  </div>
  EOT;
} else {
  echo <<<EOT
  <div class='payment'>
  <form action="payment-update.php" method="post">
  <label for="patientId">Patient ID</label>
  <input type="text" name="patientId" value="$_POST[patientId]">

  <label for="totalDue">Total Due</label>
  <input type="text" name="totalDue">

  <label for="newPayment">New Payment</label>
  <input type="text" name="newPayment">

  <input type="submit">
  </form>

  <form action="payment.php" method="get">
  <input type="submit" value="Cancel">
  </form>

  <form action="payment.php" method="post">
  <input type="text" name="update" value="1" hidden>
  <input type="text" name="patientId" value=$_POST[patientId] hidden>
  <input type="submit" value="Update">
  </form>
  </div>
  EOT;

  if (isset($_POST['update']) && (isset($_POST['patientId']))) {
    if ($stmt = $mysqli->prepare("SELECT lastUpdateDate, totalDue FROM patients WHERE patientId = ?")) {
      $stmt->bind_param("i", $_POST['patientId']);
      $stmt->execute();
      $stmt->bind_result($lastUpdateDate, $totalDue);
      while ($stmt->fetch()) {
        if ($lastUpdateDate != $currentDate) {
          echo "<p>Last Paid Date: $lastUpdateDate</p>";
          echo "<p>Previous Total: $totalDue</p>";
        } else {
          echo "<p>All up to date.</p>";
          echo "<p>Previous Total: $totalDue</p>";
        }
      }
      $stmt->close();
  }
}
}
?>
