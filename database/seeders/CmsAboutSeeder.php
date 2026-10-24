<?php

namespace Database\Seeders;

use App\Models\CmsSetting;
use Illuminate\Database\Seeder;

class CmsAboutSeeder extends Seeder
{
    public function run(): void
    {
        CmsSetting::updateOrCreate(
            ['key' => 'about_mission'],
            [
                'value' => 'The Edo State Tourism Agency (EDSTA) is a statutory public corporation established to guide, preserve, license, and promote the natural landmarks, cultural festivals, and historical heritage monuments of Edo State.',
                'label' => 'Our Mission',
                'type' => 'textarea',
                'group' => 'about'
            ]
        );

        CmsSetting::updateOrCreate(
            ['key' => 'about_text'],
            [
                'value' => 'Edo State, home to the ancient Benin Kingdom — one of the oldest and most sophisticated civilizations in Africa — boasts a rich tapestry of history, art, and culture. From the world-famous Benin Bronzes that now grace the world\'s greatest museums, to the living traditions of the Oba\'s Palace, EDSTA exists to ensure these wonders are accessible, preserved, and celebrated by all.',
                'label' => 'About EDSTA (Main Text)',
                'type' => 'textarea',
                'group' => 'about'
            ]
        );
    }
}