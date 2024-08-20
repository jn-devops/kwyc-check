<?php

namespace Homeful\KwYCCheck\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\KwYCCheck\KwYCCheckServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Homeful\\KwYCCheck\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            KwYCCheckServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_kwyc-check_table.php.stub';
        $migration->up();
        */
    }
}
