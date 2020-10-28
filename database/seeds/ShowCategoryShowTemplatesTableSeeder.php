<?php

use App\ShowCategory;
use App\ShowTemplate;
use Illuminate\Database\Seeder;

class ShowCategoryShowTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $showTemplates = ShowTemplate::all();

        $showTemplates->each(static function (ShowTemplate $showTemplate) {
            $showCategoryId = ShowCategory::all()->random(1)->first()->id;
            $showTemplate->showCategories()->sync($showCategoryId);
        });
    }
}
