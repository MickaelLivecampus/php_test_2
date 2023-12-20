<?php

require_once __DIR__ . '/../config/db.php';
require_once("./../controllers/rdvController.php");


function findAll(): false|array
{
    $sql = 'SELECT vehicules.id AS vehicule_id, vehicules.marque, vehicules.modele, vehicules.annee, vehicules.client_id, clients.nom FROM vehicules JOIN clients ON vehicules.client_id = clients.id';

    return getPDO()->query($sql)->fetchAll() ?? [];
}

function getAllCars()
{
    // $conn = connectDB();

    // $results = $conn->query("SELECT vehicules.id AS vehicule_id, vehicules.marque, vehicules.modele, vehicules.annee, vehicules.client_id, clients.nom FROM vehicules JOIN clients ON vehicules.client_id = clients.id");
    // $rows = $results->fetch_all(MYSQLI_ASSOC);

    $rows = findAll();

    $data = array_map(function ($item) {
        return [
            'id' => $item['vehicule_id'],
            'marque' => $item['marque'],
            'modele' => $item['modele'],
            'annee' => $item['annee'],
            'client' => [
                'id' => $item['client_id'],
                'nom' => $item['nom']
            ]
        ];
    }, $rows);

    // $conn->close();

    return $data;
}

function getByIdCar($vehiculeId)
{
    $conn = connectDB();

    $stmt = $conn->prepare("SELECT vehicules.id AS vehicule_id, vehicules.marque, vehicules.modele, vehicules.annee, vehicules.client_id, clients.nom FROM vehicules JOIN clients ON vehicules.client_id = clients.id WHERE vehicules.id = ?");
    $stmt->bind_param('i', $vehiculeId);
    $stmt->execute();

    $result = $stmt->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $data = [
            'id' => $item['vehicule_id'],
            'marque' => $item['marque'],
            'modele' => $item['modele'],
            'annee' => $item['annee'],
            'client' => [
                'id' => $item['client_id'],
                'nom' => $item['nom']
            ]
        ];
    } else {
        $data = null;
    }

    $stmt->close();
    $conn->close();

    return $data;
}


function createCar($marque, $modele, $annee, $client_id)
{
    try {
        checkFields(['marque' => $marque, 'modele' => $modele, 'annee' => $annee, 'client_id' => $client_id]);

        $conn = connectDB();

        $stmt = $conn?->prepare(
            'INSERT INTO vehicules (marque,modele, annee, client_id) VALUES(?,?,?,?)'
        );
        $stmt->bind_param('ssii', $marque, $modele, $annee, $client_id);
        $stmt->execute();

        $stmt->close();
        $conn->close();

        return [
            "success" => true,
        ];

    } catch (Exception $e) {

        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

function deleteCar($vehiculeId)
{
    try {
        $conn = connectDB();

        // delete RDV if exists
        deleteRDV($vehiculeId);

        $stmt = $conn->prepare('DELETE FROM vehicules WHERE id = ?');
        $stmt->bind_param('i', $vehiculeId);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Unable to find the car with the ID: $vehiculeId");
        }

        $stmt->close();
        $conn->close();

        return [
            "success" => true,
        ];

    } catch (Exception $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}

function editCar($marque, $modele, $annee, $client_id, $vehiculeId)
{

    try {
        $conn = connectDB();

        $stmt = $conn->prepare('UPDATE vehicules SET marque = ?, modele = ?, annee = ?, client_id = ? WHERE id = ?');
        $stmt->bind_param('ssiii', $marque, $modele, $annee, $client_id, $vehiculeId);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("Aucun véhicule mis à jour ou les données sont identiques.");
        }

        $stmt->close();
        $conn->close();

        return [
            "success" => true,
        ];

    } catch (Exception $e) {
        return [
            "success" => false,
            "error" => $e->getMessage()
        ];
    }
}
?>