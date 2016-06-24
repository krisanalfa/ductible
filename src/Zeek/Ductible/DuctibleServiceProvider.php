<?php

namespace Zeek\Ductible;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class DuctibleServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/conf/ductible.php' => config_path('ductible.php')
        ], 'config');
    }

    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/conf/ductible.php',
            'ductible'
        );

        $this->registerLogger();
        $this->registerHandler();
        $this->registerDuctibleClient();
        $this->registerDuctible();
        $this->registerDatabaseAbstrationLayer();
    }

    /**
     * {@inheritDoc}
     */
    public function provides()
    {
        return [
            'Elasticsearch\Client',
            'ductible.client',
            'Zeek\Ductible\Ductible',
            'ductible',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function compiles()
    {
        return [
            __DIR__.'/Ductible.php'
        ];
    }

    /**
     * Register Elasticsearch client logger.
     */
    protected function registerLogger()
    {
        $this->app->singleton('ductible.client.logger', function ($app) {
            return ClientBuilder::defaultLogger(
                $app->make('config')->get('ductible.log')
            );
        });
    }

    /**
     * Register Elasticsearch client handler.
     */
    protected function registerHandler()
    {
        $this->app->singleton('ductible.client.handler', function ($app) {
            $handler = $app->make('config')->get('ductible.handler');

            return (in_array($handler, ['default', 'single', 'multi']))
                ? ClientBuilder::{$handler.'Handler'}()
                : $app->make($handler);
        });
    }

    /**
     * Register Elasticsearch client.
     */
    protected function registerDuctibleClient()
    {
        $this->app->singleton(['Elasticsearch\Client' => 'ductible.client'], function ($app) {
            $config = $app->make('config')->get('ductible');

            $client = ClientBuilder::create()
                ->setHosts(explode('|', $config['hosts']))
                ->setRetries($config['retries'])
                ->setLogger($app->make('ductible.client.logger'))
                ->setHandler($app->make('ductible.client.handler'))
                ->setConnectionPool($config['pool'])
                ->setSelector($config['selector'])
                ->setSerializer($config['serializer'])
                ->build();

            return $client;
        });
    }

    /**
     * Register Ductible.
     */
    protected function registerDuctible()
    {
        $this->app->singleton(['Zeek\Ductible\Ductible' => 'ductible'], function ($app) {
            return new Ductible($app->make('ductible.client'), $app->make('config'));
        });
    }

    protected function registerDatabaseAbstrationLayer()
    {
        $this->app->singleton(['Doctrine\DBAL\Connection' => 'ductible.dbal.connection'], function ($app) {
            $databaseConfiguration = $app->make('config')->get(
                'database.connections.'.$app->make('config')->get('database.default')
            );

            $connectionParams = array(
                'dbname' => $databaseConfiguration['database'],
                'user' => $databaseConfiguration['username'],
                'password' => $databaseConfiguration['password'],
                'host' => $databaseConfiguration['host'],
                'driver' => 'pdo_'.$databaseConfiguration['driver'],
            );

            return DriverManager::getConnection(
                $connectionParams,
                new Configuration()
            );
        });

        $this->app->singleton(['Doctrine\DBAL\Schema\AbstractSchemaManager' => 'ductible.dbal.schema'], function ($app) {
            return $app->make('ductible.dbal.connection')->getSchemaManager();
        });
    }
}
