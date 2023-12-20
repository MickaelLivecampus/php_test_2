<?php
// Step 1 : Start the session
ob_start();
session_start();

// redirect the user if is not logged in, disable the redirection in login form
if(!isset($_SESSION["loggedin"]) && $_SERVER['REQUEST_URI'] != '/views/login.php') {
    http_response_code(403);
    header('Location: ./../views/login.php');
    exit();
} 

$currentRoute = explode("/", $_SERVER['REQUEST_URI']);

// Init hrefLink here
$hrefLink = (object) array(
    'Login' => './views/login.php',
    'Logout' => './views/logout.php',
    'Sign up' => './views/register.php',
    'Cars' => './views/car.php',
);

// if currentRoute is views then store the new link
foreach ($hrefLink as $key => $value) {
    if ($currentRoute[1] == "views") {
        $tempLink = explode("/", $value);
        $hrefLink->$key = $tempLink[2];
    }
    if (isset($_SESSION["loggedin"]) && $key == "Sign up") {
        unset($hrefLink->$key);
    }
}
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>GARAGE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="/static/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/static/assets/css/styles.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <!-- Ici, vous pouvez ajouter un logo ou un titre si vous le souhaitez -->
            <a class="navbar-brand" href="#">Garage</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <?php foreach ($hrefLink as $link => $value): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $value ?>">
                                <?= $link ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>