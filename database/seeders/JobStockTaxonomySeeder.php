<?php
namespace Database\Seeders;
use App\Models\JobLocation;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class JobStockTaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $skills = ['PHP','Laravel','JavaScript','TypeScript','React','Vue.js','Next.js','Node.js','MySQL','PostgreSQL','HTML','CSS','Bootstrap','UI/UX Design','Figma','Digital Marketing','SEO','Content Writing','Sales','Customer Support','Project Management','Data Analysis','Python','Java','AWS','Cybersecurity'];
        foreach ($skills as $name) Skill::updateOrCreate(['slug'=>Str::slug($name)], ['name'=>$name,'is_active'=>true]);
        $locations = [
            ['Ahmedabad','Gujarat','India'],['Mumbai','Maharashtra','India'],['Pune','Maharashtra','India'],['Bengaluru','Karnataka','India'],['Hyderabad','Telangana','India'],['Delhi','Delhi','India'],['Chennai','Tamil Nadu','India'],['Kolkata','West Bengal','India'],['Remote','Remote','India']
        ];
        foreach ($locations as [$city,$state,$country]) JobLocation::updateOrCreate(['slug'=>Str::slug($city.'-'.$state.'-'.$country)], ['city'=>$city,'state'=>$state,'country'=>$country,'is_active'=>true]);
    }
}
