<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supervisor;
use App\Models\User;

class SupervisorSeeder extends Seeder
{
    public function run(): void
    {
        $supervisorUsers = User::whereHas('role', function ($q) {
            $q->where('name', 'Supervisor');
        })->get();

        foreach ($supervisorUsers as $user) {
            Supervisor::create([
                'user_id' => $user->id,
                'department' => 'General Oversight',
                'shift' => rand(0, 1) ? 'Day' : 'Night',
            ]);
        }
    }
}
