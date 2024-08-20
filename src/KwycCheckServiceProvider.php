<?php

namespace Homeful\KwycCheck;

use Homeful\KwycCheck\Commands\KwycCheckCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
