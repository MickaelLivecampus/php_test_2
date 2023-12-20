<?php

require_once __DIR__ . '/../config/db.php';

// function getClients(): array
// {
//     $conn = connectDB();

//     $results = $conn->query("SELECT id, nom FROM clients");
//     $rows = $results->fetch_all(MYSQLI_ASSOC);

//     return $rows;
// }

function getClients(): array
{
    $conn = getPDO();

    $stmt = $conn->prepare("SELECT id, nom FROM clients");
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = null;
    $conn = null; 

    return $rows;
}
?>