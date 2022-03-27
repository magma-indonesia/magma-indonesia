<?php

namespace App\Console\Commands\v1;

use App\Notifications\v1\Telegram\MagmaVenTelegram as TelegramMagmaVenTelegram;
use App\TelegramNotification;
use App\v1\MagmaVen;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MagmaVenTelegram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:magma-ven';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim laporan terakhir letusan ke telegram';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function updateTelegramNotification($ven): void
    {
        TelegramNotification::updateOrCreate([
            'model' => 'ven'
        ], [
            'model_id' => $ven->erupt_id,
            'datetime' => "{$ven->erupt_tgl} {$ven->erupt_jam}:00",
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $venTelegram = TelegramNotification::where('model','ven')->first();

        $ven = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi', 'user:vg_nip,vg_nama')->lastVen()->first();
        $venDate = Carbon::createFromFormat('Y-m-d H:i', "{$ven->erupt_tgl} {$ven->erupt_jam}");

         if ($venDate->gt($venTelegram->datetime)) {
            $ven->notify(new TelegramMagmaVenTelegram($ven));
            $ven->sent_to_telegram_at = now();
            $ven->save();

            $this->updateTelegramNotification($ven);
         }
    }
}
