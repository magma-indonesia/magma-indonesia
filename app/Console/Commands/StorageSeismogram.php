<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class StorageSeismogram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:seismogram';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat symbolic link dari "public/seismogram" ke "storage/app/seismogram"';

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
        if (file_exists(public_path('seismogram'))) {
            return $this->error('Folder "public/seismogram" sudah ada.');
        }

        if (!file_exists(storage_path('app/seismogram'))) {
            File::makeDirectory(storage_path('app/seismogram'));
        }

        $this->laravel->make('files')->link(
            storage_path('app/seismogram'), public_path('seismogram')
        );

        $this->info('Shortcut Folder [public/seismogram] berhasil dibuat.');
    }
}
