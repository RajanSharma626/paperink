<?php

namespace Database\Seeders;

use App\Models\CoverTemplate;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\Uri\UriTemplate\Template;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       (new CoverTemplateSeeder())->run();
       (new TemplateSeeder())->run();
       (new UserSeeder())->run();
    }
}
