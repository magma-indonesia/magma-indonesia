<?php

namespace App\Console\Commands;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class AssignSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:superadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan role Super Admin';
    protected $role;
    protected $users;

    protected function createRole()
    {
        $this->role->name = 'Super Admin';
        $this->role->save();
        return $this;
    }

    protected function addRole()
    {
        $this->users->each(function ($nip,$key) {
            $user = User::where('nip',$nip)->first();
            $user ? $user->assignRole(['Super Admin']) : false;
        });
        return $this;
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->role = new Role();
        $this->users = collect([
            '198803152015031005',
            '198102182006041001',
            '198706202009011002'
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Sedang menambahkan role Super Admin...');
        Role::where('name','Super Admin')->exists() 
            ? $this->addRole() 
            : $this->createRole()->addRole();
        $this->info('Berhasil menambahkan role Super Admin');
    }
}
