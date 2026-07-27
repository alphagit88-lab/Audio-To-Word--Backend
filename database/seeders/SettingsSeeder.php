<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Seed the Gemini API keys into the settings table.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'gemini_api_keys'],
            ['value' => 'key1,key2']
        );
    }
}
