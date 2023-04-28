<?php

namespace App\Console\Commands\v1;

use App\Notifications\v1\Telegram\MagmaVenTelegram as TelegramMagmaVenTelegram;
use App\TelegramNotification;
use App\Traits\v1\DeskripsiLetusan;
use App\v1\MagmaVen;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Shivella\Bitly\Facade\Bitly;
use Thujohn\Twitter\Facades\Twitter;

class MagmaVenTelegram extends Command
{
    use DeskripsiLetusan;

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
            'datetime' => $ven->utc,
        ]);
    }

    protected function sendToTwitter($ven): string
    {
        $tweet = $this->deskripsiTwitter($ven);
        $url = Bitly::getUrl($this->url($ven));
        $content = "{$tweet} {$url}";

        Twitter::postTweet(['status' => $content, 'format' => 'json']);

        return $url;
    }

    protected function convertToUTC(string $datetime, string $zonearea)
    {
        switch ($zonearea) {
            case 'WIB':
                $tz = 'Asia/Jakarta';
                break;
            case 'WITA':
                $tz = 'Asia/Makassar';
                break;
            default:
                $tz = 'Asia/Jayapura';
                break;
        }

        $datetime_utc = Carbon::createFromTimeString($datetime, $tz)->setTimezone('UTC')->format('Y-m-d H:i:s');

        return $datetime_utc;
    }

    protected function updateVen(): void
    {
        $vens = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi', 'user:vg_nip,vg_nama')->whereNull('utc')->get();

        if ($vens->isNotEmpty()) {
            $vens->each(function ($ven) {
                $datetime = "{$ven->erupt_tgl} {$ven->erupt_jam}";
                $zonearea = $ven->gunungapi->ga_zonearea;
                $ven->utc = $this->convertToUTC($datetime, $zonearea);
                $ven->save();
            });
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->updateVen();

        $venTelegram = TelegramNotification::where('model','ven')->first();

        $ven = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi', 'user:vg_nip,vg_nama')->lastVen()->first();
        // $venDate = Carbon::createFromFormat('Y-m-d H:i', "{$ven->erupt_tgl} {$ven->erupt_jam}");

        $this->info($ven->utc->gt($venTelegram->datetime) ? 'True' : 'False');

        if ($ven->utc->gt($venTelegram->datetime) AND empty($ven->sent_to_telegram_at)) {
            $this->info('Telegram : Sending...');
            $ven->notify(new TelegramMagmaVenTelegram($ven));
            $this->info('Telegram : Sent!');
            $ven->sent_to_telegram_at = now();

            // $this->info('Twitter : Sending...');
            // $ven->bitly = $this->sendToTwitter($ven);
            // $this->info('Twitter : Sent!');

            $ven->save();
            $this->info('Ven : Updated!');

            $this->updateTelegramNotification($ven);
            $this->info('Telegram : Updated!');
        }
    }
}
