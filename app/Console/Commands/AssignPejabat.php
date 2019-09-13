<?php

namespace App\Console\Commands;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class AssignPejabat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:struktural';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan role Struktural PVMBG...';
    protected $role;
    protected $users;

    protected function createRole()
    {
        $this->role->name = 'Struktural';
        $this->role->save();
        return $this;
    }

    protected function addRole()
    {
        $this->users->each(function ($nip,$key) {
            $user = User::where('nip',$nip)->first();
            $user ? $user->assignRole('Struktural') : false;
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
            '197108302003122001',
            '198102182006041001',
            '196804151994031002',
            '197507042002121001',
            '196112051991031001',
            '196110301991031001',
            '196708121994032002',
            '198006212005021014',
            '197307232006041001',
            '196508231994031001',
            '196208221990031001',
            '196808152002121001'
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Sedang menambahkan role Struktural PVMBG...');
        Role::where('name','Struktural PVMBG')->exists() 
            ? $this->addRole() 
            : $this->createRole()->addRole();
        $this->info('Berhasil menambahkan role Struktural PVMBG');
    }
}
