<?php

require_once('./../partials/header.php');
require_once("./../security/csrfToken.php");
require_once('./../controllers/carController.php');
require_once('./../controllers/clientController.php');
require_once __DIR__ . '/../config/db.php';

$carController = new CarController(getPDO());

$clientsOptions = getClients();
// get the id from the url parameter
$vehiculeId = isset($_GET['id']) ? intval($_GET['id']) : null;
$error = false;

$response = $carController->findById($vehiculeId);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $marque = htmlspecialchars(filter_input(INPUT_POST, 'marque'), ENT_QUOTES);
    $modele = htmlspecialchars(filter_input(INPUT_POST, 'modele'), ENT_QUOTES);
    $annee = htmlspecialchars(filter_input(INPUT_POST, 'annee'), ENT_QUOTES);
    $clientId = htmlspecialchars(filter_input(INPUT_POST, 'client_id'), ENT_QUOTES);
    $vehiculeId = htmlspecialchars(filter_input(INPUT_POST, 'vehicle_id'), ENT_QUOTES);
    $csrfToken = filter_input(INPUT_POST, "csrf_token");

    if (verifyCSRFToken($csrfToken)) {
        $response = $carController->update($marque, $modele, $annee, $clientId, $vehiculeId);
    
        if ($response["success"]) {
            header('Location: ./car.php');
            exit();
        } else {
            $error = $response["error"];
        }
    } else {
        $error = "Invalid form submission";
    }

}
?>

<div class="container mt-5">
    <h1 class="mb-4">Tableau des Véhicules</h1>
    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <input type="hidden" class="form-control" id="vehicle_id" name="vehicle_id" value="<?= $response["id"] ?>">
        <div class="mb-3">
            <label for="marque" class="form-label">Marque :</label>
            <input type="text" class="form-control" id="marque" name="marque" value="<?= $response["marque"] ?>">
        </div>

        <div class="mb-3">
            <label for="modele" class="form-label">Modèle :</label>
            <input type="text" class="form-control" id="modele" name="modele" value="<?= $response["modele"] ?>">
        </div>

        <div class="mb-3">
            <label for="annee" class="form-label">Année :</label>
            <input type="number" class="form-control" id="annee" name="annee" min="1900" max="2023"
                value="<?= $response["annee"] ?>">
        </div>

        <div class="mb-3">
            <label for="clientSelect" class="form-label">Client :</label>
            <select name="client_id" id="clientSelect" class="form-select">
                <?php foreach ($clientsOptions as $option): ?>
                    <option value="<?php echo $option["id"]; ?>" <?= $response["client"]["id"] == $option["id"] ? "selected" : ""; ?>>
                        <?php echo $option["nom"]; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php require_once('./../partials/footer.php'); ?>