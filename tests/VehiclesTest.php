<?php


namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class VehiclesTest extends WebTestCase {

    private $vehiclesStructureKeys = ["id", "brand", "model", "year"];
    private $vehicleStructureKeys = ["id", "brand", "model", "year", "vehicleOwner"];
    
    /** @Test */    
    public function testGetVehicle() {
        
        $client = static::createClient();
        
        $client->request('GET', '/api/vehicles/2/');        
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $actualKeys = array_keys(json_decode($client->getResponse()->getContent(), true));        
        
        $this->assertEqualsCanonicalizing($this->vehicleStructureKeys, $actualKeys); 

    }
    
    /** @Test */    
    public function testGetVehicles() {
        
        $client = static::createClient();
        
        $client->request('GET', '/api/vehicleowners/1/vehicles/');
        
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $actualKeys = array_keys(json_decode($client->getResponse()->getContent(), true)[0]);        
        
        $this->assertEqualsCanonicalizing($this->vehiclesStructureKeys, $actualKeys); 

    }
    
    /** @test */
    public function testAddVehicleFailure() {

        $model = ["brand" => "Opel", "year" => 2015];
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $this->expectException(ValidationFailedException::class);

        $client->request('POST', '/api/vehicleowners/1/vehicles/',array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($model));
        
    }
    
    /** @test */
    public function testAddVehicle() {

        $model = ["brand" => "Opel", "model" => $this->modelProvider(), "year" => 2015];
        $client = static::createClient();

        $client->request('POST', '/api/vehicleowners/1/vehicles/',array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($model));
        
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        
    }
    
    /** @Test */
    public function testUpdateVehicle() {
        
        $client = static::createClient();
        $model["model"] = $this->modelProvider();
        
        $client->request('PATCH', '/api/vehicles/2/',array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($model));
        
        $this->assertEquals(202, $client->getResponse()->getStatusCode());
        
    }
    
    private function modelProvider() {
        
        $models = ["Octavia", "Octavia Scout", "Scala", "Enyaq", "Felicia", "Rapid", "Micra", "Frontera"];
        
        return $models[random_int(0, (count($models)-1))];
        
    }
    
    
}
