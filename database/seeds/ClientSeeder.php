<?php

use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = \App\Models\Company::all();
        $ids = $companies->pluck('id')->all();

        factory(\App\Models\Client::class, $companies->count() * 3)->create()->each(function ($client, $index) use($ids) {
            $newIndex = (int) floor($index / 3);
            $id = $ids[$newIndex];

            $client->company()->associate($id);
            $client->save();
        });
    }
}
