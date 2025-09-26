<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->createMany([[
                'name' => 'operator',
                'email' => 'operator@example.com',
                'password' => Hash::make('password'),
                'role_id' => Role::firstWhere('name', 'operator')->id,
            ], [
                'name' => 'manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role_id' => Role::firstWhere('name', 'manager')->id,
            ]]);

        $orders = Order::factory()
            ->withProducts(1, 5)
            ->state(fn () => ['created_at' => now()->subDays(random_int(0, 60))])
            ->createMany(random_int(75, 150));
    }
}
