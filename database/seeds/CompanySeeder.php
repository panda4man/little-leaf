<?php

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Company::class, 2)->create()->each(function ($c, $k) {
            if($k < 1) {
                $c->default = true;
            } else {
                $c->default = false;
            }

            $c->owner()->associate(\App\Models\User::first());
            $c->save();
        });
    }
}
