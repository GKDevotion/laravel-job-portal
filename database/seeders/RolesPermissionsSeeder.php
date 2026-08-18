<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cached permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->seedAdminPermissions();
        $this->seedCompanyPermissions();
        $this->seedEmployeePermissions();
        $this->seedLanguages();
        $this->seedCurrencies();
    }

    protected function seedAdminPermissions(): void
    {
        $permissions = [
            'manage companies', 'approve companies', 'manage users',
            'manage jobs', 'manage categories', 'manage languages',
            'manage currencies', 'manage settings', 'manage roles',
            'view analytics', 'manage invoices',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'admin']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $superAdmin->syncPermissions($permissions);

        $moderator = Role::firstOrCreate(['name' => 'Moderator', 'guard_name' => 'admin']);
        $moderator->syncPermissions(['manage jobs', 'manage users', 'approve companies']);
    }

    protected function seedCompanyPermissions(): void
    {
        $permissions = [
            'manage jobs', 'manage team', 'manage applications',
            'view invoices', 'edit company profile',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'company']);
        }

        // The employer account itself is always the "Owner" — full rights by default
        $owner = Role::firstOrCreate(['name' => 'Owner', 'guard_name' => 'company']);
        $owner->syncPermissions($permissions);
    }

    protected function seedEmployeePermissions(): void
    {
        $permissions = [
            'post jobs', 'edit jobs', 'review applications',
            'manage team', 'view reports',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'employee']);
        }

        $hrManager = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'employee']);
        $hrManager->syncPermissions(['post jobs', 'edit jobs', 'review applications', 'manage team', 'view reports']);

        $recruiter = Role::firstOrCreate(['name' => 'Recruiter', 'guard_name' => 'employee']);
        $recruiter->syncPermissions(['post jobs', 'edit jobs', 'review applications']);

        $viewer = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'employee']);
        $viewer->syncPermissions(['review applications']);
    }

    protected function seedLanguages(): void
    {
        $languages = [
            ['name' => 'English', 'code' => 'en', 'locale' => 'en_US', 'is_rtl' => false, 'is_default' => true, 'order' => 1],
            ['name' => 'Arabic', 'code' => 'ar', 'locale' => 'ar_SA', 'is_rtl' => true, 'is_default' => false, 'order' => 2],
            ['name' => 'French', 'code' => 'fr', 'locale' => 'fr_FR', 'is_rtl' => false, 'is_default' => false, 'order' => 3],
            ['name' => 'Hindi', 'code' => 'hi', 'locale' => 'hi_IN', 'is_rtl' => false, 'is_default' => false, 'order' => 4],
        ];

        foreach ($languages as $lang) {
            Language::firstOrCreate(['code' => $lang['code']], $lang);
        }
    }

    protected function seedCurrencies(): void
    {
        $currencies = [
            ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'exchange_rate' => 1, 'symbol_position' => 'before', 'is_default' => true],
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'exchange_rate' => 0.92, 'symbol_position' => 'before', 'is_default' => false],
            ['name' => 'Indian Rupee', 'code' => 'INR', 'symbol' => '₹', 'exchange_rate' => 83.5, 'symbol_position' => 'before', 'is_default' => false],
            ['name' => 'British Pound', 'code' => 'GBP', 'symbol' => '£', 'exchange_rate' => 0.79, 'symbol_position' => 'before', 'is_default' => false],
        ];

        foreach ($currencies as $currency) {
            Currency::firstOrCreate(['code' => $currency['code']], $currency);
        }
    }
}
