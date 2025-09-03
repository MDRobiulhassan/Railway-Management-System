<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NidDbSeeder extends Seeder
{
    public function run(): void
    {
        $nidData = [
            [
                'nid_number' => '1234567890123',
                'name' => 'John Doe',
                'dob' => '1995-05-15',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nid_number' => '9876543210987',
                'name' => 'Jane Smith',
                'dob' => '1990-08-22',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nid_number' => '1111222233334',
                'name' => 'Ahmed Rahman',
                'dob' => '1988-12-10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nid_number' => '5555666677778',
                'name' => 'Fatima Khan',
                'dob' => '1992-03-18',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nid_number' => '12345678901234567',
                'name' => 'Mohammad Ali',
                'dob' => '1985-07-25',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('nid_db')->insert($nidData);
    }
}
