<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Repositories\CompanyRepository;
use App\Transformers\CompanyTransformer;
use Storage;

class CompaniesController extends Controller
{
    /**
     * @param $id
     * @param CompanyRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, CompanyRepository $repo)
    {
        $company = $repo->find($id);
        $company = fractal()->item($company, new CompanyTransformer())->toArray();

        if($company) {
            return response()->json([
                'success' => true,
                'data'    => $company,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 404);
    }

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
            if($request->hasFile('photo') && $request->file('photo')) {
                $file = $request->file('photo')->store('companies');

                $company->update([
                    'photo' => $file
                ]);
            }

            $company = fractal()->item($company, new CompanyTransformer())->toArray();

            return response()->json([
                'success' => true,
                'data'    => $company,
            ], 203);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }

    /**
     * @param Company $company
     * @param CreateCompanyRequest $req
     * @param CompanyRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Company $company, CreateCompanyRequest $req, CompanyRepository $repo)
    {
        $data = $req->except('photo');

        // Store new photo and save path
        // Delete old photo
        if($req->hasFile('photo') && $req->file('photo')) {
            $oldPhoto = $company->photo;
            $file = $req->file('photo')->store('companies');
            $data['photo'] = $file;
            Storage::delete($oldPhoto);
        }

        $success = $repo->update($company->id, $data);

        if($success) {
            $company = $repo->find($company->id);

            if($company) {
                $company = fractal()->item($company, new CompanyTransformer())
                    ->includeClients()
                    ->toArray();
            }

            return response()->json(['success' => true, 'data' => $company], 200);
        }

        return response()->json(['success' => false], 400);
    }

    /**
     * @param Company $company
     * @param CompanyRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Company $company, CompanyRepository $repo)
    {
        $success = $repo->delete($company->id);

        if($success) {
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false
        ], 400);
    }
}
