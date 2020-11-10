<?php

//Activate when we get the data base

//$result = mysqli_queary($link, "SELECT firstName, lastName
//FROM users
//WHERE id = (SELECT userId FROM patients WHERE patientId = ?);

//Check access level for admind privlages(later)
$PatientID = $_POST["Patient_ID"];
echo <<<EOT
<h1>Additional Information of Patient</h1>


<label for="Patient ID">Patient ID:</label>
<input type="text" name="Patient ID" value=$PatientID>

<label for="Patient Name">Patient Name:</label>
<input type="text" name="Patient Name">

<label for="Group">Group:</label>
<input type="text" name="Group">

<label for="Admission Date">Admission Date:</label>
<input type="text" name="Admission Date">

<input type="submit" value="Submit">
<input type="reset" value="Clear">

EOT;
?>
