<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class JobStockAdminSeeder extends Seeder
{
 public function run(): void
 {
  $email = env('JOBSTOCK_SUPER_ADMIN_EMAIL'); $password = env('JOBSTOCK_SUPER_ADMIN_PASSWORD'); $name = env('JOBSTOCK_SUPER_ADMIN_NAME', 'JobStock Super Admin');
  if (!$email || !$password) { $this->command?->warn('Set JOBSTOCK_SUPER_ADMIN_EMAIL and JOBSTOCK_SUPER_ADMIN_PASSWORD in .env before running this seeder.'); return; }
  User::updateOrCreate(['email'=>$email], ['name'=>$name,'password'=>Hash::make($password),'role'=>User::ROLE_SUPER_ADMIN,'status'=>'active','email_verified_at'=>now()]);
  $this->command?->info('JobStock super admin account created/updated.');
 }
}
