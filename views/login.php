<?php

require_once('./../partials/header.php');
require_once("./../controllers/userController.php");
require_once("./../security/csrfToken.php");

$error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(filter_input(INPUT_POST, "username"));
    $password = htmlspecialchars(filter_input(INPUT_POST, "password"));
    $csrfToken = filter_input(INPUT_POST, "csrf_token");


    if (verifyCSRFToken($csrfToken)) {
        loginUser($username, $password);
    } else {
        $error = "Invalid form submission";
    }
}
?>

<?php if (!isset($_SESSION["loggedin"])): ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php if ($error): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                <div class="card">
                    <div class="card-header">
                        Connexion
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="margin">
        <p>Welcome
            <?= $_SESSION["username"] ?>, you are logged in !
        </p>
    </div>
<?php endif; ?>

<?php require_once('./../partials/footer.php'); ?>