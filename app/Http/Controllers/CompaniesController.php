<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Transformers\CompanyTransformer;

class CompaniesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $companies = Company::orderBy('name')->get();
        $companies = fractal()
            ->collection($companies, new CompanyTransformer())
            ->includeClients()
            ->toArray();

        return view('companies.index', [
            'companies' => $companies
        ]);
    }

    /**
     * @param Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getSelect(Company $company)
    {
        session('company', $company->id);

        session()->flash('success', 'Switched to ' . $company->name);
        return redirect()->back();
    }
}
