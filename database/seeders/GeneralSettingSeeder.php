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
            'name' => 'السيدابي مبارك عمر السيدابي للترحيلات',
            'location' => null,
            'phone' => '0912711162',
            'phone_2' => null,
            'email' => null,
            'logo' => 'logo.png',
            'note' => null,
        ]);
    }
}
