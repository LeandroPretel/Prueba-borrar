<?php

use App\Artist;
use Illuminate\Database\Seeder;

class ArtistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->create('PrimigeniA');
        $this->create('Marea');
        $this->create('Flamenco Ogíjares');
        $this->create('Rocío Jurado');
    }

    /**
     * Creates a new Artist and saves it
     * @param $name
     */
    private function create(string $name): void
    {
        $artist = new Artist();
        $artist->name = $name;
        $artist->webName = $name;
        $artist->ticketName = $name;
        $artist->save();
    }
}
