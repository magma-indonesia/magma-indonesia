<?php

namespace App\Console\Commands;

use App\Blacklist;
use App\Jobs\UpdateBlacklistLog;
use App\StatistikAccess;
use Illuminate\Console\Command;

class UpdateBlacklist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:blacklist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update blacklist';

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
     * @return mixed
     */
    public function handle()
    {
        $blacklists = Blacklist::orderBy('hit', 'desc')->pluck('ip_address');
        $accesess = StatistikAccess::where('hit', '>', 1000)
            ->orderBy('updated_at', 'desc')
            ->limit(20)
            ->pluck('ip_address');

        $this->info('Updating Blacklist....');
        $diff = $accesess->diff($blacklists);

        if ($diff->isNotEmpty()) {
            $diff->each(function ($ip) {
                Blacklist::firstOrCreate(['ip_address' => $ip]);
            });

            return $this->info('Blacklist updated!');
        }

        $this->info('Already updated!');

    }
}
