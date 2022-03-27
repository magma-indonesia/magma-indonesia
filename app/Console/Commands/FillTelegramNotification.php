<?php

namespace App\Console\Commands;

use App\TelegramNotification;
use App\v1\MagmaVar;
use App\v1\MagmaVen;
use Illuminate\Console\Command;

class FillTelegramNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:telegram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi latest telegram';

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
        $ven = MagmaVen::select('erupt_id','erupt_tgl','erupt_jam')
                ->orderBy('erupt_tgl', 'desc')
                ->orderBy('erupt_jam', 'desc')
                ->first();

        $var = MagmaVar::select('no', 'var_data_date', 'var_log')
            ->orderBy('var_log', 'desc')
            ->orderBy('no', 'desc')
            ->first();

        TelegramNotification::updateOrCreate([
            'model' => 'var'
        ],[
            'model_id' => $var->no,
            'datetime' => $var->var_log,
        ]);

        TelegramNotification::updateOrCreate([
            'model' => 'ven'
        ], [
            'model_id' => $ven->erupt_id,
            'datetime' => "{$ven->erupt_tgl} {$ven->erupt_jam}:00",
        ]);
    }
}
