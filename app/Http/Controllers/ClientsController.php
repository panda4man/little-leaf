<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = Client::query();

        if(request()->has('company_id')) {
            $clients->whereHas('company', function ($q) {
                $q->where('id', request()->get('company_id'));
            });
        }

        $clients = $clients->get();

        return view('clients.index', [
            'clients' => $clients
        ]);
    }
}
