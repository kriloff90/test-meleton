<?php

namespace App\Console\Commands;

use App;

use Illuminate\Console\Command;

class IdeHelperMake extends Command
{
    protected $signature = 'ide-helper:make';

    protected $description = 'Generate all the IDE helper files on local environment only.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (App::environment('local')) {
            $this->call('ide-helper:generate');
            $this->call('ide-helper:meta');
            $this->call('ide-helper:models', [
                '--nowrite' => true
            ]);
        }

        return true;
    }
}
