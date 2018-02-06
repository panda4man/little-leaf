<?php

namespace App\Providers;

use App\Repositories\ClientRepository;
use App\Repositories\CompanyRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->bind(ClientRepository::class, function () {
            return new ClientRepository();
        });

        $this->app->bind(CompanyRepository::class, function () {
            return new CompanyRepository();
        });
    }

    public function provides()
    {
        return [
            ClientRepository::class,
            CompanyRepository::class,
        ];
    }
}