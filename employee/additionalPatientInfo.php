<?php
//Activate when we get the data base
$mysqli = new mysqli("localhost", "root", "", "retirement");
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

$query = "SELECT firstName, lastName FROM users WHERE id = (SELECT userId FROM patients WHERE patientId = ?)";
//Checking the select query
$stmt = $mysqli->prepare($query);
//binding patientID
$stmt->bind_param("i", $_POST['patientId']);
//executing SELECT query
$stmt->execute();
//binding firstName, lastName
$stmt->bind_result($firstName, $lastName);
//Check access level for admind privlages(later)
    while ($stmt->fetch()) {
      printf( <<<EOT
        <p>Name: %s %s</p>
        <form action="additionalPatientInfo.php" method="post">
        <label for="patientId">ID:</label>
        <input type="text" name="patientId" value=$_POST[patientId]>
        <label for="group">Group:</label>
        <input type="text" name="group">
        <label for="admissionDate">Admission Date:</label>
        <input type="date" name="admissionDate">
        <input type="submit">
        <input type="reset" value="Clear">
        </form>
      EOT, $firstName, $lastName);
    }
echo <<<EOT
</table>
EOT;
//use form to enter the group and admission date
if (isset($_POST['group']) and isset($_POST['admissionDate'])) {
$group = $_POST['group'];
$admissionDate = $_POST['admissionDate'];
$patientId = $_POST['patientId'];
$query = "UPDATE patients SET admissionDate= ?, groupId=? WHERE patientId = ?";
//Checking the insert patients query
$stmt = $mysqli->prepare($query);
//Posting group and admissionDate
$stmt->bind_param("sss", $admissionDate, $group, $patientId);
//execute the insert query for patients
$stmt->execute();
}
?>
