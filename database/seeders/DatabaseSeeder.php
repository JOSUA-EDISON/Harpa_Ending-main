<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'ranggayuda2003@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'admin2',
            'email' => 'josuaedison6@gmail.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        $this->call(LocationsSeeder::class);
    }
}
