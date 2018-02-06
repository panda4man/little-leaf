<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
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

        $this->call(CompanySeeder::class);
        $this->call(ClientSeeder::class);
    }
}
