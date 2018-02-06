<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CompanyRepository;

class CompaniesController extends Controller
{
    /**
     * @param CreateCompanyRequest $request
     * @param CompanyRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateCompanyRequest $request, CompanyRepository $repo)
    {
        $current = User::find(auth()->id());
        $company = $repo->create($request->all(), $current);

        if($company) {
            return response()->json([
                'success' => true
            ], 203);
        }

        return response()->json([
            'success' => false
        ], 400);
    }
}
