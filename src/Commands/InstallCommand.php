<?php

namespace Featica\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'featica:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial install & setup of Featica';

    /**
     * Constructs a new instance.
     */
    public function __construct()
    {
        parent::__construct();

        if (is_array(app()['config']['featica'])) {
            $this->setHidden(true);
        }
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Featica Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'featica-provider']);

        $this->comment('Publishing Featica Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'featica-assets']);

        $this->comment('Publishing Featica Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'featica-config']);

        $this->comment('Registering App\Providers\FeaticaServiceProvider.php...');
        $this->registerFeaticaServiceProvider();

        $this->info('Featica installed successfully 👌');
    }

    /**
     * Register the Featica service provider in the application configuration file.
     *
     * - Thanks to https://github.com/laravel/telescope for this implementation
     *
     * @return void
     */
    protected function registerFeaticaServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\FeaticaServiceProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol,
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol."        {$namespace}\Providers\FeaticaServiceProvider::class,".$eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/FeaticaServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/FeaticaServiceProvider.php'))
        ));
    }
}
