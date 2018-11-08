<?php

// tests/Controller/HomeControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;

class HomeControllerTest extends WebTestCase
{
    public function testShowHome()
    {
		//$session = new Session(new MockFileSessionStorage());

		$client = static::createClient();

        $client->request('GET', '/home');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}