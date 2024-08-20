<?php

namespace Homeful\KwYCCheck\Commands;

use Illuminate\Console\Command;

class KwYCCheckCommand extends Command
{
    public $signature = 'kwyc-check';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
