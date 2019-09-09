<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class ResetPassword extends Command
{

    protected $users;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Digunakan untuk mereset password MAGMA v2 secara global';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->users = User::all();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Resetting Password....');

        foreach ($this->users as $user) {
            $user->password = 'esdm1234';
            $user->save();
        }

        $this->info('Reset Password berhasil');
    }
}
