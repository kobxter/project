<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MeetingRoom;

class MeetingRoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MeetingRoom::insert([
            ['name' => 'ห้องประชุมใหญ่', 'capacity' => 50, 'location' => 'อาคาร A ชั้น 3'],
            ['name' => 'ห้องประชุมเล็ก', 'capacity' => 10, 'location' => 'อาคาร B ชั้น 1'],
            ['name' => 'ห้องอบรม', 'capacity' => 20, 'location' => 'อาคาร C ชั้น 2'],
        ]);
    }
}
