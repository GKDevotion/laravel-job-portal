<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@jobportal.test'],
            [
                'name' => 'Super Admin',
                'password' => 'ChangeMe123!', // hashed automatically via the "hashed" cast
                'is_super_admin' => true,
                'status' => true,
            ]
        );

        $admin->assignRole('Super Admin');

        $this->command->info('Default admin created -> admin@jobportal.test / ChangeMe123! (change this immediately)');
    }
}
