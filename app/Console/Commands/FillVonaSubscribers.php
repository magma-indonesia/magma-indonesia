<?php

namespace App\Console\Commands;

use App\VonaSubscriber;
use Illuminate\Console\Command;

class FillVonaSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:vona-subscriber';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi VONA Email subscribers';

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
        $json = storage_path() . '/json/vona-subscribers.json';
        $subscribers = collect(json_decode(file_get_contents($json)));

        $subscribers->each(function ($sub) {
            VonaSubscriber::firstOrCreate([
                'email' => $sub->email
            ], [
                'real' => $sub->real,
                'exercise' => $sub->exercise,
                'pvmbg' => $sub->pvmbg,
                'status' => $sub->status,
            ]);
        });

        $this->info('Update VONA Subscribers berhasil');
    }
}
