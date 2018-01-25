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
        DB::table('statuses')->insert([
            ['level' => 1, 'status' => 'Level I (Normal)'],
            ['level' => 2, 'status' => 'Level II (Waspada)'],
            ['level' => 3, 'status' => 'Level III (Siaga)'],
            ['level' => 4, 'status' => 'Level IV (Awas)']
        ]);
    }
}
