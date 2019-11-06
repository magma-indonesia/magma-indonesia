<?php

namespace App\Console\Commands;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class AssignStaffMgaBandung extends Command
{
    protected $staffs = [
        '196203131991031002',
        '196209171991032001',
        '196610081994031001',
        '196307091994031002',
        '196905171997032001',
        '197003261998031001',
        '196612191996031001',
        '196303191992031001',
        '196309162002121002',
        '197106231998032001',
        '196511071994032001',
        '196807071992031018',
        '197107232005021002',
        '197108302003122001',
        '198010202006041002',
        '197308132006041001',
        '198102182006041001',
        '198006152006042002',
        '197012021992031002',
        '197901072006041001',
        '197810292006041001',
        '197707152006041001',
        '198203092006042001',
        '197410052006041001',
        '198012192008011001',
        '196404041992031001',
        '198111022008012001',
        '198512192010011013',
        '196707131992031003',
        '196705081992031001',
        '196712181991031001',
        '196807091993031002',
        '197403272002121001',
        '199012042014022003',
        '198104172006042002',
        '198007142006041001',
        '198212172006042001',
        '198510172014021002',
        '198510082014022001',
        '198602082009121002',
        '198502112015031002',
        '198803152015031005',
        '199108212015032003',
        '198911032015032004',
        '198706202009011002'
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:staff_mga';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan Role Staff MGA';
    protected $role;
    protected $users;

    protected function createRole()
    {
        $this->role->name = 'Staff MGA';
        $this->role->save();
        return $this;
    }

    protected function addRole()
    {
        $this->users->each(function ($nip,$key) {
            $user = User::where('nip',$nip)->first();
            $user ? $user->assignRole('Staff MGA') : false;
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
        $this->info('Sedang menambahkan role Staff MGA...');

        $this->role = new Role();
        $this->users = collect($this->staffs);

        Role::where('name','Staff MGA')->exists() 
            ? $this->addRole() 
            : $this->createRole()->addRole();

        $this->info('Berhasil menambahkan role Staff MGA');
    }
}
