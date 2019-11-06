<?php

namespace App\Console\Commands;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class AssignStaffBtuBandung extends Command
{
    protected $staffs = [
        '196112051991031001',
        '197004261993032008',
        '196103241987031001',
        '196007151987032001',
        '196501011987032001',
        '196405201984032001',
        '198110212006042002',
        '196801171990032001',
        '198103052006041003',
        '198211302006041001',
        '196903181992031002',
        '196812291993031003',
        '196804211992032001',
        '196007061984031002',
        '196107031987032001',
        '196303251988032001',
        '196111281988032001',
        '196112261989031002',
        '196311141988031001',
        '196905071992031002',
        '196805221993032001',
        '196605161993031001',
        '196304271993031002',
        '197005091994032001',
        '196211291981031001',
        '196712161989031002',
        '196909271992031002',
        '196201021989031002',
        '196101031984031002',
        '197011142007011027',
        '197806182007011001',
        '197510122007012002',
        '196212311992031007',
        '197109092008111001',
        '197610272008111001',
        '196507042009101001',
        '196306212007011001',
        '197407102008111001',
        '197204142008111001',
        '196304011989031002',
        '196304301991031001',
        '196010022006041007',
        '196205052007011002',
        '196805142007011002',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:staff_btu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan Role Staff BTU';
    protected $role;
    protected $users;

    protected function createRole()
    {
        $this->role->name = 'Staff BTU';
        $this->role->save();
        return $this;
    }

    protected function addRole()
    {
        $this->users->each(function ($nip,$key) {
            $user = User::where('nip',$nip)->first();
            $user ? $user->assignRole('Staff BTU') : false;
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
        $this->info('Sedang menambahkan role Staff BTU...');

        $this->role = new Role();
        $this->users = collect($this->staffs);

        Role::where('name','Staff BTU')->exists() 
            ? $this->addRole() 
            : $this->createRole()->addRole();

        $this->info('Berhasil menambahkan role Staff BTU');
    }
}
