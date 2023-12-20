<?php

require_once('./../partials/header.php');
require_once("./../security/csrfToken.php");
require_once('./../controllers/carController.php');
require_once('./../controllers/clientController.php');
require_once('./../utils/form.php');

$clientsOptions = getClients();
// get the id from the url parameter
$vehiculeId = isset($_GET['id']) ? intval($_GET['id']) : null;
$error = false;

$response = getByIdCar($vehiculeId);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $marque = filter_input(INPUT_POST, 'marque');
    $modele = filter_input(INPUT_POST, 'modele');
    $annee = filter_input(INPUT_POST, 'annee');
    $client_id = filter_input(INPUT_POST, 'client_id');
    $vehiculeId = filter_input(INPUT_POST, 'vehicle_id');
    $csrfToken = filter_input(INPUT_POST, "csrf_token");

    if (verifyCSRFToken($csrfToken)) {
        $response = editCar($marque, $modele, $annee, $client_id, $vehiculeId);
    
        if ($response["success"]) {
            header('Location: ./car.php');
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