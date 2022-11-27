<?php

namespace App\Console\Commands\v1;

use App\v1\PressRelease;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FillSlugForPressRelease extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:slug-press-release';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengisi kolom slug pada Press Release';

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
        $this->info('Updating slug...');
        $presses = PressRelease::select('id', 'judul', 'slug')
            ->whereNull('slug')->get();

        $presses->each(function ($press) {
            $press->slug = Str::slug($press->judul);
            $press->save();
        });

        $this->info('Update slug berhasil');
    }
}
