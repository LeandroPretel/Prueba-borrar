<?php

use App\Client;
use App\User;
use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = User::where('email', 'cliente@redentradas.es')->first();

        $client = new Client();
        $client->user()->associate($user);
        $client->name = 'Pepe';
        $client->surname = 'Juárez';
        $client->phone = 213232123;
        $client->nif = '21357133T';
        $client->save();
    }
}
