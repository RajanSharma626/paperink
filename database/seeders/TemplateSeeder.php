<?php

namespace Database\Seeders;

use App\Models\ResumeTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResumeTemplate::insert([
            [
                'name' => 'Bold',
                'slug' => 'bold',
                'preview_img' => '/img/templates/1.png',
                'component' => 'ResumeTemplate1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clean',
                'slug' => 'clean',
                'preview_img' => '/img/templates/2.png',
                'component' => 'ResumeTemplate2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Modern',
                'slug' => 'modern',
                'preview_img' => '/img/templates/3.png',
                'component' => 'ResumeTemplate3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'preview_img' => '/img/templates/4.png',
                'component' => 'ResumeTemplate4',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
