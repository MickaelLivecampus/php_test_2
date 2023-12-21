<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../controllers/carController.php';

class CarControllerTest extends TestCase
{
    private PDO $conn;
    private carController $carController;

    protected function setUp(): void {
        $this->conn = getTestingPDO();
        $this->carController = new CarController($this->conn);
    }

    private function cleanUpDataBase($id): void {
        $sql = 'DELETE FROM vehicules WHERE id = :vehiculeId';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':vehiculeId', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function testFindAll() {
        $result = $this->carController->findAll();

        // test if the result is an array
        $this->assertIsArray($result);
    }

    public function testFindById() {
        $result = $this->carController->findById(1);

        // test if the result is an array
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        // test if each key exists in the result
        foreach (["id", "marque", "modele", "annee", "client"] as $key) {
            $this->assertArrayHasKey($key, $result);
        }

        $this->assertEquals('Toyota', $result['marque']);
        $this->assertEquals('Camry', $result['modele']);
    }

    public function testSuccessCreate() {
        $fakeData = [
            'marque' => 'Renault',
            'modele'=> 'Clio',
            'annee' => 2015,
            'client_id' => 2
        ];

        $result = $this->carController->create(...$fakeData);

        // test if the creation is successful
        $this->assertTrue($result['success']);
        
        // get the last id to delete from the database
        $lastId = $this->conn->lastInsertId();
        $this->cleanUpDataBase($lastId);
    }

    public function testErrorCreate() {
        $fakeData = [
            'marque' => '',
            'modele' => '',
            'annee' => 2015,
            'client_id' => 2
        ];

        $result = $this->carController->create(...$fakeData);

        // test if the creation is not successful
        $this->assertFalse($result['success']);
    }

    public function testUpdate() {

        $fakeDataCreate = [
            'marque' => 'Renault',
            'modele'=> 'Clio',
            'annee' => 2015,
            'client_id' => 2
        ];

        $result = $this->carController->create(...$fakeDataCreate);

        $lastId = $this->conn->lastInsertId();

        $fakeDataUpdate = [
            'marque' => 'Porsche',
            'modele' => 'GT',
            'annee' => 2022,
            'clientId' => 3,
            'vehiculeId' => $lastId
        ];
        
        $result = $this->carController->update(...$fakeDataUpdate);
        
        // test if the creation is successful
        $this->assertTrue($result['success']);
        
        $data = $this->carController->findById($fakeDataUpdate["vehiculeId"]);

        $this->assertEquals('Porsche', $data['marque']);
        $this->assertEquals('GT', $data['modele']);
    }

    public function testDelete() {
        $fakeDataCreate = [
            'marque' => 'Renault',
            'modele'=> 'Clio',
            'annee' => 2015,
            'client_id' => 2
        ];

        $result = $this->carController->create(...$fakeDataCreate);

        $lastId = $this->conn->lastInsertId();

        $result = $this->carController->destroy($lastId);

        // test if the creation is successful
        $this->assertTrue($result['success']);
    }
}

?>