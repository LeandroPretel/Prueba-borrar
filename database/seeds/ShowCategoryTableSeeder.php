<?php

use App\ShowCategory;
use Illuminate\Database\Seeder;

class ShowCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->create('Conciertos');
        $this->create('Musicales');
        $this->create('Teatro');
        $this->create('Infantil');
        $this->create('Humor y variedades');
        $this->create('Flamenco');
        $this->create('Música clásica');
        $this->create('Ballet');
        $this->create('Lírica');
        $this->create('Carnaval');
        $this->create('Danza');
        $this->create('Magia');
        $this->create('Familiar');
        $this->create('Circo');
        $this->create('Monólogos');
    }

    /**
     * Creates a new ShowCategory and saves it
     * @param $name
     */
    private function create(string $name): void
    {
        $artist = new ShowCategory();
        $artist->name = $name;
        $artist->save();
    }
}
