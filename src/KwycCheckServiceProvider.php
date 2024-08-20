<?php

namespace Homeful\KwycCheck;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Homeful\KwycCheck\Commands\KwycCheckCommand;

class KwycCheckServiceProvider extends PackageServiceProvider
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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_kwyc_check_table')
            ->hasCommand(KwycCheckCommand::class);
    }
}
