<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GeneralSetting::query()->firstOrCreate(['id' => 1], [
            'name' => 'شركة بيان لتطوير الاعمال',
            'location' => null,
            'phone' => null,
            'phone_2' => null,
            'email' => null,
            'logo' => null,
            'note' => null,
        ]);
    }
}
