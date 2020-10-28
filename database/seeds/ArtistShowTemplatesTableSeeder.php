<?php

use App\Artist;
use App\ShowTemplate;
use Illuminate\Database\Seeder;

class ArtistShowTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $showTemplates = ShowTemplate::all();

        $showTemplates->each(function (ShowTemplate $showTemplate) {
            $artistId = Artist::all()->random(1)->first()->id;
            $showTemplate->artists()->sync($artistId);
        });


//            $clientFavouriteAccount->account()->associate($account);
//            $clientFavouriteAccount->client()->associate($client);
//            $showTemplate->update();
//        $clientFavouriteAccount = new \App\ClientFavouriteAccount();
//        $clientFavouriteAccount->account()->associate($account);
//        $clientFavouriteAccount->client()->associate($client);
//        $clientFavouriteAccount->save();
    }
}
