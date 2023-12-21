<?php

require_once('./../partials/header.php');
require_once("./../security/csrfToken.php");
require_once("./../controllers/userControllers.php");

$username = htmlspecialchars(filter_input(INPUT_POST, "username"));
$password = htmlspecialchars(filter_input(INPUT_POST, "password"));

if (verifyCSRFToken($csrfToken)) {
    createUser($username, $password);
} else {
    $error = "Invalid form submission";
}
?>

<form method="POST">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" />

    <label for="password">Password</label>
    <input type="password" name="password" id="password" />

    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
    <input type="submit" value="Register" />
</form>

<?php require_once('./../partials/footer.php'); ?>