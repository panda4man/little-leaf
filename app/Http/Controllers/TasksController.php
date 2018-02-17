<?php

namespace App\Http\Controllers;

class TasksController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('tasks.index');
    }
}
