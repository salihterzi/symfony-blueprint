<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('POST', '/auth/login', ['email' => 'admin@admin.com','password'=>'123456']);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);


        $client->xmlHttpRequest('POST', '/ajax/test');
        $this->assertResponseIsSuccessful();
    }

}
