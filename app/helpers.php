<?php

function urlActive($url = '', $class = 'disabled') {
    if(request()->is($url))
        return $class;

    return '';
}

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

function isCurrentCompany($company) {
    return session('company') == $company->id;
}