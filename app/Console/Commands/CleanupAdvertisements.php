<?php

namespace App\Console\Commands;

use App\Modules\Advertisements\Service\AdvertisementCleanupService;
use Illuminate\Console\Command;

class CleanupAdvertisements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advertisement:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivates active advertisements that match criteria';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        app(AdvertisementCleanupService::class)->cleanup();

        return 0;
    }
}
