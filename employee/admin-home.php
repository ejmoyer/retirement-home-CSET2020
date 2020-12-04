<?php
session_start();
if ($_SESSION['access'] != 1) {
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
<a href="../authentication/regApproval.php">Registration Approval</a>
<a href="create_roster.php">Create Roster</a>
<a href="view_roster.php">View Roster</a>
<a href="rolePage.php">All Roles Page</a>
<a href="employee-list.php">All Employees List</a>
<a href="additionalPatientInfo.html">Additional Patient Info</a>
EOT;
?>