<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            MediaSeeder::class,
            MenuCatalogSeeder::class,
            SpecialtySeeder::class,
            GallerySeeder::class,
            OrderSeeder::class,
            ContactMessageSeeder::class,
            SettingSeeder::class,
            EventSeeder::class,
            CateringRequestSeeder::class,
            CustomerSeeder::class,
            SmsLogSeeder::class,
        ]);
    }
}
