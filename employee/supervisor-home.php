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
if ($_SESSION['access'] > 2) {
  header('Location: ../home.html');
  exit;
}

// logout button
echo <<<EOT
<form action="../authentication/logout.php" method="get">
<input type=submit value=Logout>
</form>
EOT;

echo <<<EOT
<h1>Supervisor Home Page</h1>
<a class='adHome' href="../authentication/regApproval.php">Registration Approval</a>
<a class='adHome' href="create_roster.php">Create Roster</a>
<a class='adHome' href="view_roster.php">View Roster</a>
<a class='adHome' href="employee-list.php">All Employees List</a>
<a class='adHome' href="additionalPatientInfo.html">Additional Patient Info</a>
<a class='adHome' href="doctor-app.php">New Doctor Appointment</a>
<a class='adHome' href="admin-report.php">Missed Patient Activity</a>
EOT;
?>
