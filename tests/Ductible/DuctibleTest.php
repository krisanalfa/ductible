<?php

namespace Ductible;

use Zeek\Ductible\DuctibleServiceProvider;

class DuctibleTest extends AbstractTestCase
{
    protected $ductible;

    protected function setUp()
    {
        $this->app = $app = $this->createApplication();

        $app->register(DuctibleServiceProvider::class);

        $this->ductible = $app->make('ductible');
    }

    public function testIndexingIsWorking()
    {
        $response = $this->ductible->index([
            'id' => 1,
            'index' => 'ductible',
            'type' => 'ductible',
            'body' => [
                'foo' => 'foo',
                'bar' => 'bar',
                'baz' => 'baz',
            ]
        ]);

        $this->assertTrue(is_array($response));

        $this->assertTrue(array_key_exists('created', $response));
    }

    public function testGetIsWorking()
    {
        $response =  $this->ductible->get([
            'index' => 'ductible',
            'type' => 'ductible',
            'id' => 1,
        ]);

        $this->assertTrue(is_array($response));
        $this->assertEquals($response['_index'], 'ductible');
        $this->assertEquals($response['_type'], 'ductible');
        $this->assertEquals($response['_id'], '1');
        $this->assertEquals($response['_source'], [
            'foo' => 'foo',
            'bar' => 'bar',
            'baz' => 'baz',
        ]);
    }

    public static function tearDownAfterClass()
    {
        $test = new static();
        $app = $test->createApplication();

        $app->register(DuctibleServiceProvider::class);

        $app->make('ductible')
            ->getClient()
            ->indices()
            ->delete(['index' => 'ductible']);
    }
}
