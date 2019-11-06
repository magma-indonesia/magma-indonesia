<?php

namespace App\Console\Commands;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class AssignStaffBpptkg extends Command
{
    protected $staffs = [
        '196112101991032001',
        '196206121990031001',
        '196708101993031001',
        '196505231991032002',
        '196412271993031005',
        '195908031994031001',
        '195809261991031002',
        '196211251994031002',
        '196107061990032003',
        '196812211993032007',
        '196205161988031002',
        '196102071988031001',
        '196304291996031001',
        '196706051988032002',
        '197802232006042001',
        '198008272005021001',
        '196805011991031004',
        '197511122005022001',
        '196009291985031001',
        '197211032005021001',
        '198103252008012001',
        '196308081986031004',
        '197012101992031001',
        '198507082010121002',
        '197611252002122001',
        '198105252006042001',
        '198710102014021002',
        '198305282006042001',
        '198608242014021003',
        '198503272015032001',
        '198911272015032005',
        '198710262015032006',
        '197403182005021001',
        '198008262005021001',
        '197104092007011002',
        '196201032007011001',
        '199404192015032001',
        '198010022008111001',
        '198006292009102001',
        '197507052009101001',
        '197302262007011001',
        '196106062007011003',
    ];
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:staff_bpptkg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan Role Staff BPPTKG';
    protected $role;
    protected $users;

    protected function createRole()
    {
        $this->role->name = 'Staff BPPTKG';
        $this->role->save();
        return $this;
    }

    protected function addRole()
    {
        $this->users->each(function ($nip,$key) {
            $user = User::where('nip',$nip)->first();
            $user ? $user->assignRole('Staff BPPTKG') : false;
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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Sedang menambahkan role Staff BPPTKG...');

        $this->role = new Role();
        $this->users = collect($this->staffs);

        Role::where('name','Staff BPPTKG')->exists() 
            ? $this->addRole() 
            : $this->createRole()->addRole();

        $this->info('Berhasil menambahkan role Staff BPPTKG');
    }
}
