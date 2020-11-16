<?php
// open a new sql connection
$mysqli = new mysqli("localhost", "root", "", "retirement");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if (isset($_POST['role']) &&
isset($_POST['first_name']) &&
isset($_POST['last_name']) &&
isset($_POST['email']) &&
isset($_POST['password']) &&
isset($_POST['phone']) &&
isset($_POST['date_of_birth'])) {
  // TODO: Fix this so it grabs the role from the sql Database and then use it 
  $stmt = $mysqli->prepare("INSERT INTO users (firstName, lastName, roleId, age, email, password, phone, dateOfBirth, approved) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
  $stmt->bind_param("sssssii", $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['password'], $_POST['phone'], $_POST['date_of_birth']);
  $stmt->execute();

  printf("%d Row inserted.\n", $stmt->affected_rows);

  /* close statement and connection */
  $stmt->close();

}
$mysqli->close();
?>
