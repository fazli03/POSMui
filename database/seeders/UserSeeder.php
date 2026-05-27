<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role sudah ada
        $ownerRole = Role::firstOrCreate(['name' => 'owner']);
        $kasirRole = Role::firstOrCreate(['name' => 'kasir']);
        $dapurRole = Role::firstOrCreate(['name' => 'dapur']);

        // Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@pos.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('owner123'),
            ]
        );
        $owner->assignRole($ownerRole);

        // Kasir
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@pos.com'],
            [
                'name' => 'Kasir',
                'password' => Hash::make('kasir123'),
            ]
        );
        $kasir->assignRole($kasirRole);

        // Dapur
        $dapur = User::firstOrCreate(
            ['email' => 'dapur@pos.com'],
            [
                'name' => 'Dapur',
                'password' => Hash::make('dapur123'),
            ]
        );
        $dapur->assignRole($dapurRole);
    }
}
