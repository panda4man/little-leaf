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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('works')->truncate();
        DB::table('projects')->truncate();
        DB::table('clients')->truncate();
        DB::table('companies')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
