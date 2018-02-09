<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Transformers\ClientTransformer;
use App\Transformers\CompanyTransformer;

class ClientsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $clients = Client::query();
        $companies = Company::orderBy('name')->get();

        if(request()->has('company_id')) {
            $clients->whereHas('company', function ($q) {
                $q->where('id', request()->get('company_id'));
            });
        }

        $clients = $clients->orderBy('name')->get();
        $clients = fractal()->collection($clients, new ClientTransformer())
            ->includeCompany()
            ->includeProjects()
            ->toArray();
        $companies = fractal()->collection($companies, new CompanyTransformer())->toArray();

        return view('clients.index', [
            'clients'   => $clients,
            'companies' => $companies,
        ]);
    }

    /**
     * @param Client $client
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Client $client)
    {
        $clientJson = fractal()->item($client, new ClientTransformer())->includeCompany()->toArray();

        return view('clients.show', [
            'client' => $client,
            'json'   => $clientJson,
        ]);
    }
}
