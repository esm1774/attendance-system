<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            SubjectSeeder::class, // أضف هذا السطر
            SchoolSeeder::class,
            StageSeeder::class,
            GradeSeeder::class,

        ]);
    }
}


