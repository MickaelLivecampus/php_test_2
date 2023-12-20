<?php

require_once __DIR__ . '/../config/db.php';

function getClients(): array
{
    $conn = connectDB();

    $results = $conn->query("SELECT id, nom FROM clients");
    $rows = $results->fetch_all(MYSQLI_ASSOC);

    return $rows;
}

?>