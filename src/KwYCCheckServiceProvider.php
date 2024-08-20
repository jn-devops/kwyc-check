<?php

namespace Homeful\KwYCCheck;

use Homeful\KwycCheck\Commands\KwYCCheckCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasConfigFile(['kwyc-check'])
            ->hasViews()
            ->hasMigration('create_kwyc_check_table')
            ->hasCommand(KwYCCheckCommand::class);
    }
}
