<?php
// open a new sql connection
$mysqli = new mysqli("localhost", "root", "", "retirement");

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
// If all of the fields were filled out, prepare an insert statement.
if (isset($_POST['role']) &&
isset($_POST['first_name']) &&
isset($_POST['last_name']) &&
isset($_POST['email']) &&
isset($_POST['password']) &&
isset($_POST['phone']) &&
isset($_POST['date_of_birth'])) {
  if ($stmt = $mysqli->prepare("INSERT INTO users (firstName, lastName, roleId, email, password, phone, dateOfBirth, approved) VALUES (?, ?, ?, ?, ?, ?, ?, 0)")) {
  $stmt->bind_param("ssissii", $_POST['first_name'], $_POST['last_name'], $_POST['role'], $_POST['email'], $_POST['password'], $_POST['phone'], $_POST['date_of_birth']);
  // execute the statement.
  $stmt->execute();
  /* close statement */
  $stmt->close();


  if ($stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?")) {
    $stmt->bind_param("s", $_POST['email']);
    // execute the query
    $stmt->execute();
    // store the result
    $stmt->store_result();
    // create the ID variable
    $stmt->bind_result($id);
    // bind the results to id
    $stmt->fetch();
    // close the statement
    $stmt->close();
    // checks if they filled out these boxes (so if they're a patient)
    if (isset($_POST['family_code']) && isset($_POST['emergency_contact']) && isset($_POST['relation_to_contact'])) {
      $stmt = $mysqli->prepare("INSERT INTO patients (userId, familyCode, emergencyContact, emergencyRelation) VALUES (?, ?, ?, ?)");
      // bind the parameters
      $stmt->bind_param("iiss", $id, $_POST['family_code'], $_POST['emergency_contact'], $_POST['relation_to_contact']);
      // execute the query
      $stmt->execute();
      // close the statement.
      $stmt->close();
      // If their role is an employee
    } elseif ($_POST['role'] <= 4) {
      $stmt = $mysqli->prepare("INSERT INTO employees (userId) VALUES (?)");
      // bind the parameters
      $stmt->bind_param("i", $id);
      // execute the query
      $stmt->execute();
      // close the statement.
      $stmt->close();
      }
    }
  }
}
$mysqli->close();
?>
