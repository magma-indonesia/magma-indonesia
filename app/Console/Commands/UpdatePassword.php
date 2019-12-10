<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdatePassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:password {nip : NIP/KTP dari User} {password=esdm1234 : Masukkan Password baru}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update atau reset password User';

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
        $nip = $this->argument('nip');
        $password = $this->argument('password');

        if ($user = \App\User::whereNip($nip)->first()) {
            $this->info('Updating Data Users....');
            $user->password = $password;
            $user->save();
            return $this->info('Update Password berhasil');
        }

        return $this->error('User tidak ditemukan');

    }
}
