<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses_desc')->insert([                                
            
            ['status' => 'Level I (Normal)'],
            ['status' => 'Level II (Waspada)'],
            ['status' => 'Level III (Siaga)'],
            ['status' => 'Level IV (Awas)']
            
        ]);
    }
}
