<?php



namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class VehicleOwnersTest extends WebTestCase {

    private $ownerStructureKeys = ["id", "name", "address"];
    private $client;
    
    protected function setUp(): void {
        
        $this->client = static::createClient();
        
    }
    
    /** @Test */
    public function testGetOwner() {
        
        $this->client->request('GET', '/api/vehicleowners/2/');
        
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        
        $actualKeys = array_keys(json_decode($this->client->getResponse()->getContent(), true));

        $this->assertEqualsCanonicalizing($this->ownerStructureKeys, $actualKeys); 

    }

    /** @test */
    public function testAddOwner() {

        $model = ["name" => "Václav Plachý", "address" => "Jevíčko 21"];

        $this->client->request('POST', '/api/vehicleowners/',array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($model));
        
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        
    }

    /** @test */
    public function testUpdateOwner() {

        $model = ["name" => "Jan Žárský"];        
        
        $this->client->request('PATCH', '/api/vehicleowners/1/', array(), array(), array('CONTENT_TYPE' => 'application/json'), json_encode($model));
        
        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
        
    }
    
    
}
