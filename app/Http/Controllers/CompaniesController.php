<?php

namespace App\Http\Controllers;

class CompaniesController extends Controller
{
    public function index()
    {
        return view('companies.index');
    }
}
