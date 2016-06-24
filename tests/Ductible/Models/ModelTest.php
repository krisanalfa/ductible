<?php

namespace Ductible\Models;

use Ductible\AbstractTestCase;
use Illuminate\Support\Collection;
use Zeek\Ductible\DuctibleServiceProvider;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class ModelTest extends AbstractTestCase
{
    protected static $app;

    protected static $ductible;

    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/bootstrap.php';

        $test = new static();

        static::$app = $test->createApplication();

        static::$app->register(DuctibleServiceProvider::class);

        // require_once __DIR__.'/migrator.php';
        // require_once __DIR__.'/seeder.php';

        static::$ductible = static::$app->make('ductible');
    }

    public function testModelCanSyncAllRecords()
    {
        $return = Husband::indexAll();

        $this->assertEquals(Husband::count(), $return->count());
    }

    public function testModelIsIndexable()
    {
        $husband = Husband::first();
        $index = $husband->index();

        $this->assertTrue(is_array($index));
        $this->assertTrue(array_key_exists('created', $index));
        $this->assertTrue($husband->indexed());
    }

    public function testIndexedModelIsSearchable()
    {
        $husband = Husband::first();

        $husband->index();

        $this->assertTrue($husband->indexed());
    }

    public function testIndexedModelIsPruneable()
    {
        $husband = Husband::first();

        $husband->index();

        $husband->prune();

        $this->assertFalse($husband->indexed());
    }

    public static function tearDownAfterClass()
    {
        try {
            $husband = Husband::first();

            static::$ductible->getClient()->deleteByQuery([
                'index' => $husband->getIndexableName(),
                'type' => $husband->getIndexableType(),
                'body' => [
                    'query' => [
                        'match_all' => [],
                    ]
                ],
            ]);
        } catch (Missing404Exception $e) {
            // It's okay, don't throw up
        }
    }
}
