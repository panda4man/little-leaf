<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\CreateClientRequest;
use App\Http\Controllers\Controller;
use App\Repositories\ClientRepository;
use App\Repositories\CompanyRepository;

class ClientsController extends Controller
{
    public function store(CreateClientRequest $request, ClientRepository $clientRepo, CompanyRepository $compRepo)
    {
        $company = $compRepo->find($request->get('company_id'));
        $client = $clientRepo->create($request->all(), $company);

        if($client) {
            return response()->json([
                'success' => true
            ], 203);
        }

        return response()->json([
            'success' => false
        ], 400);
    }
}
