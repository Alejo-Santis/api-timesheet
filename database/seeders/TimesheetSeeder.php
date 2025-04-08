<?php

namespace Database\Seeders;

use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimesheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'email' => 'admin@example.com',
        ]);

        for ($i = 0; $i < 10; $i++) {
            $in = Carbon::now()->subDays(rand(1, 30))->setTime(8, 0);
            $out = (clone $in)->addHours(8);

            Timesheet::create([
                'user_id' => $user->id,
                'type' => 'work',
                'day_in' => $in,
                'day_out' => $out,
            ]);
        }

        for ($i = 0; $i < 3; $i++) {
            $in = Carbon::now()->subDays(rand(1, 30))->setTime(8, 0);
            $out = (clone $in)->addHours(6);

            Timesheet::create([
                'user_id' => $user->id,
                'type' => 'absence',
                'day_in' => $in,
                'day_out' => $out,
            ]);
        }
    }
}
