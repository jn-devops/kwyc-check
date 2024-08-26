<?php

namespace Homeful\KwYCCheck\Tests;

use Homeful\KwYCCheck\Providers\EventServiceProvider as KyWCCheckEventServiceProvider;
use Spatie\SchemalessAttributes\SchemalessAttributesServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Homeful\KwYCCheck\KwYCCheckServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Homeful\Contacts\ContactsServiceProvider;

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
            ContactsServiceProvider::class,
            SchemalessAttributesServiceProvider::class,
            KyWCCheckEventServiceProvider::class
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        config()->set('data.validation_strategy', 'always');
        config()->set('data.max_transformation_depth', 5);
        config()->set('data.throw_when_max_transformation_depth_reached', 5);

        $migration = include __DIR__.'/../database/migrations/create_leads_table.php.stub';
        $migration->up();
    }
}
