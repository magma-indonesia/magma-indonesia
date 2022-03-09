<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class FillUser extends Command
{

    /**
     * Users collection from json file
     *
     * @var Collection
     */
    protected $users;

    /**
     * Emails collection from json file
     *
     * @var Collection
     */
    protected $emails;

    /**
     * EmailESDM collection from json file
     *
     * @var Collection
     */
    protected $emailESDM;

    /**
     * Eselons collection from json file
     *
     * @var Collection
     */
    protected $eselons;

    protected function users(): Collection
    {
        return collect();
    }

    protected function emails(): Collection
    {
        return collect();
    }

    protected function emailESDM(): Collection
    {
        return collect();
    }

    protected function fillUser()
    {

    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate User';

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
        return 0;
    }
}
