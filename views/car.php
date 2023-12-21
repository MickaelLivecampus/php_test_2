<?php

require_once('./../partials/header.php');
require_once("./../security/csrfToken.php");
require_once('./../controllers/carController.php');
require_once __DIR__ . '/../config/db.php';

$carController = new CarController(getPDO());

$vehicules = $carController->findAll();
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['vehicule_id'])) {
    $vehiculeId = htmlspecialchars(filter_input(INPUT_POST, 'vehicule_id'));
    $csrfToken = filter_input(INPUT_POST, "csrf_token");


    if (verifyCSRFToken($csrfToken)) {
        $result = $carController->destroy($vehiculeId);
    
        if ($result["success"]) {
            header('Location: ./car.php');
            exit();
        } else {
            $error = $result["error"];
        }
    } else {
        $error = "Invalid form submission";
    }
}
?>

<div class="container mt-4">
    <h2>Car dashboard</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Marque</th>
                <th scope="col">Modèle</th>
                <th scope="col">Année</th>
                <th scope="col">Client</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicules as $vehicle): ?>
                <tr>
                    <td>
                        <?= $vehicle["id"] ?>
                    </td>
                    <td>
                        <?= $vehicle["marque"] ?>
                    </td>
                    <td>
                        <?= $vehicle["modele"] ?>
                    </td>
                    <td>
                        <?= $vehicle["annee"] ?>
                    </td>
                    <td>
                        <?= $vehicle["client"]["nom"] ?>
                    </td>
                    <td>
                        <div>
                            <button class="btn btn-danger delete-button" data-id="<?= $vehicle["id"] ?>">Delete</button>
                            <a href="./editcar.php?id=<?= $vehicle["id"] ?>" class="btn btn-warning">Edit</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="./newcar.php" class="btn btn-success">Add</a>
</div>

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Confirm the delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete ?
            </div>
            <div class="modal-footer">
                <form method="POST" id="deleteForm">
                    <input type="hidden" name="vehicule_id" id="vehiculeIdToDelete" value="">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var deleteButtons = document.querySelectorAll('.delete-button');
        var vehiculeIdInput = document.getElementById('vehiculeIdToDelete');

        deleteButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var vehiculeId = button.getAttribute('data-id');
                vehiculeIdInput.value = vehiculeId;
                new bootstrap.Modal(document.getElementById('deleteConfirmationModal')).show();
            });
        });
    });
</script>

<?php require_once('./../partials/footer.php'); ?>