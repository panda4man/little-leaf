<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\CreateClientRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\Company;
use App\Repositories\ClientRepository;
use App\Repositories\CompanyRepository;
use App\Transformers\ClientTransformer;

class ClientsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $projects = Client::orderBy('name')->get();
        $projects = fractal()->collection($projects, new ClientTransformer())
            ->parseIncludes(request()->get('includes'))
            ->toArray();

        return response()->json([
            'success' => true,
            'data'    => $projects,
        ]);
    }

    /**
     * @param CreateClientRequest $request
     * @param ClientRepository $clientRepo
     * @param CompanyRepository $compRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateClientRequest $request, ClientRepository $clientRepo, CompanyRepository $compRepo)
    {
        $company = $compRepo->find($request->get('company_id'));
        $client = $clientRepo->create($request->all(), $company);

        if($client) {
            $client = fractal()->item($client, new ClientTransformer)
                ->includeProjects()
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

    /**
     * @param Client $client
     * @param UpdateClientRequest $req
     * @param ClientRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Client $client, UpdateClientRequest $req, ClientRepository $repo)
    {
        $company = null;

        if($req->has('company_id')) {
            $company = Company::find($req->get('company_id'));
        }

        $success = $repo->update($client->id, $req->all(), $company);

        if($success) {
            $client = $repo->find($client->id);
            $client = fractal()->item($client, new ClientTransformer())
                ->includeCompany()
                ->includeProjects()
                ->toArray();

            return response()->json([
                'data' => $client,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }

    /**
     * @param Client $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Client $client)
    {
        try {
            $success = $client->delete();
        } catch (\Exception $e) {
            $success = false;
        }

        if($success) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }
}
