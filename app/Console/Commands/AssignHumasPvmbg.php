<?php

namespace App\Console\Commands;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class AssignHumasPvmbg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:humas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan role Humas PVMBG';
    protected $role;
    protected $users;

    protected function createRole()
    {
        $this->role->name = 'Humas PVMBG';
        $this->role->save();
        return $this;
    }

    protected function assignRole()
    {
        $this->users->each(function ($nip,$key) {
            $user = User::where('nip',$nip)->first();
            $user ? $user->syncRoles(['Humas PVMBG']) : false;
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
            '196812291993031003',
            '198211302006041001',
            '198110212006042002'
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Sedang menambahkan role Humas PVMBG...');
        Role::where('name','Humas PVMBG')->exists() 
            ? $this->assignRole() 
            : $this->createRole()->assignRole();
        $this->info('Berhasil menambahkan role Humas PVMBG');
    }
}
