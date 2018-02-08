<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\CreateClientRequest;
use App\Http\Controllers\Controller;
use App\Repositories\ClientRepository;
use App\Repositories\CompanyRepository;
use App\Transformers\ClientTransformer;

class ClientsController extends Controller
{
    public function store(CreateClientRequest $request, ClientRepository $clientRepo, CompanyRepository $compRepo)
    {
        $company = $compRepo->find($request->get('company_id'));
        $client = $clientRepo->create($request->all(), $company);

        if($client) {
            $client = fractal()->item($client, new ClientTransformer)
                ->includeCompany()
                ->toArray();

            return response()->json([
                'success' => true,
                'data'    => $client,
            ], 203);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }
}
