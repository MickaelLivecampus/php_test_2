<?php
// Step 1 : Recover the session
session_start();
// Step 2 : Close the session
session_destroy();
// Step 3 : Redirect the user to the login
header('Location: login.php');
exit();