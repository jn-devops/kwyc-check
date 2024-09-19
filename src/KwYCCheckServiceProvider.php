<?php

namespace Homeful\KwYCCheck;

use Spatie\LaravelPackageTools\PackageServiceProvider;
use Homeful\KwYCCheck\Commands\KwYCCheckCommand;
use Spatie\LaravelPackageTools\Package;

class KwYCCheckServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('kwyc-check')
            ->hasConfigFile(['data', 'media-library', 'kwyc-check'])
            ->hasViews()
            ->hasRoute('api')
            ->hasRoute('web')
            ->hasMigration('create_leads_table')
        ;
    }
}
