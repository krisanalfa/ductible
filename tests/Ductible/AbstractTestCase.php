<?php

namespace Ductible;

use PHPUnit_Framework_TestCase;
use Illuminate\Foundation\Testing\Concerns;

abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    use Concerns\InteractsWithContainer,
        Concerns\InteractsWithDatabase;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = new \Illuminate\Foundation\Application(
            dirname(dirname(__DIR__)).'/vendor/laravel/laravel'
        );

        $app->singleton(
            \Illuminate\Contracts\Http\Kernel::class,
            \App\Http\Kernel::class
        );

        $app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            \App\Console\Kernel::class
        );

        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \App\Exceptions\Handler::class
        );

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }
}
