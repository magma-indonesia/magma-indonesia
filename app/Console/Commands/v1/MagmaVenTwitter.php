<?php

namespace App\Console\Commands\v1;

use App\Traits\v1\DeskripsiLetusan;
use App\v1\MagmaVen;
use Illuminate\Console\Command;
use Shivella\Bitly\Facade\Bitly;
use Thujohn\Twitter\Facades\Twitter;

class MagmaVenTwitter extends Command
{
    use DeskripsiLetusan;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twitter:magma-ven';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim laporan terakhir letusan ke twitter pvmbg_';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function sendToTwitter($ven): string
    {
        $tweet = $this->deskripsiTwitter($ven);
        $url = Bitly::getUrl($this->url($ven));
        $content = "{$tweet} {$url}";

        Twitter::postTweet(['status' => $content, 'format' => 'json']);

        return $url;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ven = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi', 'user:vg_nip,vg_nama')->lastVen()->first();

        $this->info('Twitter : Sending...');
        $ven->bitly = $this->sendToTwitter($ven);
        $this->info('Twitter : Sent!');
        $ven->save();
    }
}
