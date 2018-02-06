<?php

function currentCompany() {
    $cId = session('company');
    $company = null;

    if($cId) {
        /** @var \App\Repositories\CompanyRepository $company */
        $companyRepo = app()->make(\App\Repositories\CompanyRepository::class);

        $company = $companyRepo->find($cId);
    }

    return $company;
}

function currentCompanySet() {
    return session('company');
}