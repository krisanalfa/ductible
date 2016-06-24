<?php

namespace Ductible;

use Zeek\Ductible\DuctibleServiceProvider;

class DuctibleSearchTest extends AbstractTestCase
{
    protected static $app;

    protected static $ductible;

    public static function setUpBeforeClass()
    {
        $test = new static();

        static::$app = $app = $test->createApplication();

        $app->register(DuctibleServiceProvider::class);

        static::$ductible = $app->make('ductible');

        $response = static::$ductible->index([
            'id' => 1,
            'index' => 'ductible_search',
            'type' => 'ductible_search',
            'body' => [
                'text' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus reiciendis nisi, sed explicabo maxime rem consectetur incidunt perspiciatis, quibusdam deleniti iste quod, qui quasi eveniet ab. Ipsa at corporis blanditiis.',
                'tag' => 'wow',
                'much' => 'doge',
            ]
        ]);
    }

    public function testIndexingIsWorking()
    {
        $result = static::$ductible->search([
            'type' => 'ductible_search',
            'body' => [
                'query' => [
                    'match' => [
                        'text' => 'ipsum',
                    ]
                ],
            ]
        ]);
    }

    public static function tearDownAfterClass()
    {
        static::$app->make('ductible.client')
            ->indices()
            ->delete(['index' => 'ductible_search']);
    }
}
