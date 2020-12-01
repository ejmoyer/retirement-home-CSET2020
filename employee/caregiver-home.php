<?php
session_start();

$mysqli = new mysqli('localhost', 'root', '', 'retirement');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

echo <<<EOT
<h1>Caregiver's Home</h1>

<form action="caregiver-home.php" method="post">
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
?>