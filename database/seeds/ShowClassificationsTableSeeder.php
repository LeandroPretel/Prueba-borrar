<?php

use App\ShowClassification;
use Illuminate\Database\Seeder;

class ShowClassificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->create('Todos los públicos');
    }

    /**
     * Creates a new showClassification and saves it
     * @param $name
     */
    private function create(string $name): void
    {
        $showClassification = new ShowClassification();
        $showClassification->name = $name;
        $showClassification->save();
    }
}
