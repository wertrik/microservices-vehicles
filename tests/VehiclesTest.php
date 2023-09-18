<?php


namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Exception\ValidationFailedException;


class VehiclesTest extends WebTestCase {

    private $vehiclesStructureKeys = ["id", "brand", "model", "year"];
    private $vehicleStructureKeys = ["id", "brand", "model", "year", "vehicleOwner"];
    private $client;
    
    protected function setUp(): void {
        
        $this->client = static::createClient();
    }


    /** @Test */    
    public function testGetVehicle() {

        $this->client->request('GET', '/api/vehicles/2/');        
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
        $actualKeys = array_keys(json_decode($this->client->getResponse()->getContent(), true));        
        
        $this->assertEqualsCanonicalizing($this->vehicleStructureKeys, $actualKeys); 

    }
    
    /** @Test */    
    public function testGetVehicles() {

        $this->client->request('GET', '/api/vehicleowners/1/vehicles/');
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
        $actualKeys = array_keys(json_decode($this->client->getResponse()->getContent(), true)[0]);        
        
        $this->assertEqualsCanonicalizing($this->vehiclesStructureKeys, $actualKeys); 

    }
    
    /** @test */
    public function testAddVehicleFailure() {

        $model = ["brand" => "Opel", "year" => 2015];
        $this->client->catchExceptions(false);
        
        $this->expectException(ValidationFailedException::class);

        $this->client->request('POST', '/api/vehicleowners/1/vehicles/',array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($model));
        
    }
    
    /** @test */
    public function testAddVehicle() {

        $model = ["brand" => "Opel", "model" => $this->modelProvider(), "year" => 2015];

        $this->client->request('POST', '/api/vehicleowners/1/vehicles/',array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($model));
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        
    }
    
    /** @Test */
    public function testUpdateVehicle() {
        
        $model["model"] = $this->modelProvider();
        
        $this->client->request('PATCH', '/api/vehicles/2/',array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($model));
        
        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
        
    }
    
    private function modelProvider() {
        
        $models = ["Octavia", "Octavia Scout", "Scala", "Enyaq", "Felicia", "Rapid", "Micra", "Frontera"];
        
        return $models[random_int(0, (count($models)-1))];
        
    }
    
    
}
