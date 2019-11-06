<?php

namespace App\Console\Commands;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Console\Command;

class AssignStaffMgbBandung extends Command
{
    protected $staffs = [
        '195904061989031001',
        '196708121994032002',
        '197009041997032001',
        '196809221995031001',
        '196404301994031001',
        '196309111991031001',
        '196705271990031001',
        '196311231986031001',
        '196808152002121001',
        '198006212005021014',
        '197308122006041001',
        '198210042006041002',
        '197909182008011001',
        '198711072010121002',
        '198710152010121003',
        '196209071992031001',
        '196802021992031001',
        '197611112006042001',
        '198912222014022001',
        '198712072014021003',
        '199109162015032007',
        '198810102015032005',
        '199104242015032006',
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:staff_mgb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan Role Staff MGB';
    protected $role;
    protected $users;

    protected function createRole()
    {
        $this->role->name = 'Staff MGB';
        $this->role->save();
        return $this;
    }

    protected function addRole()
    {
        $this->users->each(function ($nip,$key) {
            $user = User::where('nip',$nip)->first();
            $user ? $user->assignRole('Staff MGB') : false;
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
        $this->info('Sedang menambahkan role Staff MGB...');

        $this->role = new Role();
        $this->users = collect($this->staffs);

        Role::where('name','Staff MGB')->exists() 
            ? $this->addRole() 
            : $this->createRole()->addRole();

        $this->info('Berhasil menambahkan role Staff MGB');
    }
}
