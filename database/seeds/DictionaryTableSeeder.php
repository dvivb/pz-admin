<?php

use Illuminate\Database\Seeder;

class DictionaryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Dictionary::class, 'house_structure', 5)->create();
        factory(App\Dictionary::class, 'house_structure_child', 10)->create();

        factory(App\Dictionary::class, 'annexe_structure', 5)->create();
        factory(App\Dictionary::class, 'annexe_structure_child', 10)->create();

        factory(App\Dictionary::class, 'attach', 5)->create();
        factory(App\Dictionary::class, 'attach_child', 10)->create();

        factory(App\Dictionary::class, 'structure', 5)->create();
        factory(App\Dictionary::class, 'structure_child', 10)->create();

        factory(App\Dictionary::class, 'equipment', 5)->create();
        factory(App\Dictionary::class, 'equipment_child', 10)->create();

        factory(App\Dictionary::class, 'land_status', 5)->create();
        factory(App\Dictionary::class, 'land_status_child', 10)->create();

        factory(App\Dictionary::class, 'young_crop', 5)->create();
        factory(App\Dictionary::class, 'young_crop_child', 10)->create();
    }
}
