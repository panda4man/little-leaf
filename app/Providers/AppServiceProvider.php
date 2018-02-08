<?php

namespace App\Providers;

use App\Facades\ModelHashId;
use App\Searches\WorkSearch;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'project'     => \App\Models\Project::class,
            'deliverable' => \App\Models\Deliverable::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->bind(WorkSearch::class, function () {
            return new WorkSearch();
        });

        $this->app->singleton(ModelHashId::class, function () {
            return new \Hashids\Hashids(config('services.hashid.key'), config('services.hashid.padding'));
        });
    }
}
