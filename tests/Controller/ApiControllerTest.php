<?php

// tests/Controller/ApiControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testGetUsers()
    {
		$client = static::createClient();

        $crawler = $client->request('GET', '/api/v1/users');

        //test api get users status code 
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $products = json_decode($client->getResponse()->getContent(), true);
        
        //test response type
        $this->assertInternalType('array', $products);

        //test nb products
        $this->assertCount(12, $products);
    }
}