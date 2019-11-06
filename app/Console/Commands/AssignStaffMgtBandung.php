<?php

namespace App\Console\Commands;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class AssignStaffMgtBandung extends Command
{
    protected $staffs = [
        '196101221987031001',
        '195809071989031001',
        '195808081990031001',
        '196308231993031001',
        '197704302006041002',
        '196508231994031001',
        '196208221990031001',
        '196112171989031002',
        '196203091988031001',
        '196206041991031001',
        '196008151987031003',
        '197407112006041002',
        '197211102006041001',
        '197307232006041001',
        '197912182006042002',
        '198008062006041002',
        '197810252006042001',
        '196701081987031002',
        '196203201990091001',
        '197011282005021001',
        '198310232009012002',
        '198503232009011002',
        '198808282014022003',
        '198904182014021004',
        '198905292014021004',
        '198710222015031001',
        '199003132015032002',
        '198703072015031003',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:staff_mgt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan Role Staff MGT';
    protected $role;
    protected $users;

    protected function createRole()
    {
        $this->role->name = 'Staff MGT';
        $this->role->save();
        return $this;
    }

    protected function addRole()
    {
        $this->users->each(function ($nip,$key) {
            $user = User::where('nip',$nip)->first();
            $user ? $user->assignRole('Staff MGT') : false;
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
        $this->info('Sedang menambahkan role Staff MGT...');

        $this->role = new Role();
        $this->users = collect($this->staffs);

        Role::where('name','Staff MGT')->exists() 
            ? $this->addRole() 
            : $this->createRole()->addRole();

        $this->info('Berhasil menambahkan role Staff MGT');
    }
}
