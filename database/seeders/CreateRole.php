<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateRole extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()->create([
            'id' => 'Admin',
            'name' => 'Admin',
            'description' => 'Admin manage all user, dashboard',
        ]);
        Role::factory()->create([
            'id' => 'Member',
            'name' => 'Member',
            'description' => 'Join dashboard',
        ]);
        Role::factory()->create([
            'id' => 'Leader',
            'name' => 'Leader',
            'description' => 'Manage member, dashboard',
        ]);
    }
}
