<?php

namespace Database\Seeders;

use App\Models\CoverTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoverTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CoverTemplate::insert([
            [
                'name' => 'Bold',
                'slug' => 'bold',
                'preview_img' => '/img/templates/cover-letter/1.png',
                'component' => 'BoldTemplate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clean',
                'slug' => 'clean',
                'preview_img' => '/img/templates/cover-letter/2.png',
                'component' => 'CleanTemplate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Modern',
                'slug' => 'modern',
                'preview_img' => '/img/templates/cover-letter/3.png',
                'component' => 'ModernTemplate',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'preview_img' => '/img/templates/cover-letter/4.png',
                'component' => 'ProfessionalTemplate',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
