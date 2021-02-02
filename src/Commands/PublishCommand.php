<?php

namespace Featica\Commands;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'featica:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Republish Featica assets';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'featica-assets',
            '--force' => true,
        ]);
    }
}
