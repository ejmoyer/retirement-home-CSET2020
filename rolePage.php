<?php
// link to database

// query the database for all current roles

// create two textboxes that you can type into (new role and access level)
// and a submit button
echo <<<EOT
query goes here

<label for="newRole">New Role</label>
<input type="text" name="newRole">

<label for="access">Access Level</label>
<input type="text" name="access">

<input type="submit">
EOT
?>
