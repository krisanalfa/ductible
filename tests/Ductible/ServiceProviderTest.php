<?php

namespace Ductible;

use Elasticsearch\Client;
use Zeek\Ductible\Ductible;
use Zeek\Ductible\DuctibleServiceProvider;

class ServiceProviderTest extends AbstractTestCase
{
    public function testBaseClassIsPresent()
    {
        $this->assertTrue(class_exists(Ductible::class));
    }

    public function testServiceProviderIsPresent()
    {
        $this->assertTrue(class_exists(DuctibleServiceProvider::class));
    }

    public function testServiceProviderIsRegisterable()
    {
        $app = $this->createApplication();

        $app->register(DuctibleServiceProvider::class);

        $this->assertTrue($app->bound(Client::class));
        $this->assertTrue($app->bound('ductible.client'));
        $this->assertTrue($app->bound(Ductible::class));
        $this->assertTrue($app->bound('ductible'));
    }

    public function testDuctibleIsWellProvided()
    {
        $app = $this->createApplication();

        $app->register(DuctibleServiceProvider::class);

        $this->assertTrue($app->make(Client::class) instanceof Client);
        $this->assertTrue($app->make('ductible.client') instanceof Client);
        $this->assertTrue($app->make(Ductible::class) instanceof Ductible);
        $this->assertTrue($app->make('ductible') instanceof Ductible);
    }
}
