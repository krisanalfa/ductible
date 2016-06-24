<?php

namespace Ductible;

use Zeek\Ductible\DuctibleServiceProvider;

class ClientTest extends AbstractTestCase
{
    public function testClientIsWorking()
    {
        $app = $this->createApplication();

        $app->register(DuctibleServiceProvider::class);

        $client = $app->make('ductible.client');

        $this->assertTrue($client->ping());

        $this->assertTrue(is_array($client->info()));
    }
}
