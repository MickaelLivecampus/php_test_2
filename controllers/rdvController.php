<?php

require_once __DIR__ . '/../config/db.php';


function deleteRDV($vehiculeId)
{
    try {
        $conn = connectDB();

        // Suppression des rendez-vous liés
        $stmt = $conn->prepare('DELETE FROM rendezvous WHERE vehicule_id = ?');
        $stmt->bind_param('i', $vehiculeId);
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
?>