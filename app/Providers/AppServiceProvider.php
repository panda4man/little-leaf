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
        // Load IDE helper anywhere not prod
        if($this->app->environment() !== 'production' && $this->app->environment() !== 'prod') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        // Bind WorkSearch to container
        $this->app->bind(WorkSearch::class, function () {
            return new WorkSearch();
        });

        // Create new singleton to encapsulate instantiating a hashid instance
        // with the project specific configuration parameters
        $this->app->bind(ModelHashId::class, function () {
            return new \Hashids\Hashids(config('services.hashid.key'), config('services.hashid.padding'), config('services.hashid.alphabet'));
        });
    }
}
