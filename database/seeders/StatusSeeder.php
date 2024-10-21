<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['status' => Status::NEW_ORDER, 'code' => 'NEW_ORDER', 'badge' => 'danger'],
            ['status' => Status::IN_PROGRESS, 'code' => 'IN_PROGRESS', 'badge' => 'warning'],
            ['status' => Status::COMPLETED, 'code' => 'COMPLETED', 'badge' => 'success'],
            ['status' => Status::CANCELLED, 'code' => 'CANCELLED', 'badge' => 'secondary'],
        ];

        // Insert statuses into the database
        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['code' => $status['code']],
                [
                    'status' => $status['status'],
                    'badge' => $status['badge'],
                ]
            );
        }
    }
}
