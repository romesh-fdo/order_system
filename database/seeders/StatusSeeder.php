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
            ['status' => 'New Order', 'code' => tatus::NEW_ORDER, 'badge' => 'danger'],
            ['status' => 'In progress', 'code' => Status::IN_PROGRESS, 'badge' => 'warning'],
            ['status' => 'Completed', 'code' => Status::COMPLETED, 'badge' => 'success'],
            ['status' => 'Cancelled', 'code' => Status::CANCELLED, 'badge' => 'secondary'],
        ];

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
