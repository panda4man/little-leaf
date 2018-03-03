<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Transformers\ClientTransformer;
use App\Transformers\ProjectTransformer;

class ProjectsController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $projects = Project::all();
        $projects = fractal()->collection($projects, new ProjectTransformer())
            ->includeClient()
            ->includeWork()
            ->includeDeliverables()
            ->toArray();
        $clients = Client::orderBy('name')->get();
        $clients = fractal()->collection($clients, new ClientTransformer())->toArray();

        return view('projects.index')->with([
            'projects' => $projects,
            'clients'  => $clients,
        ]);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Project $project)
    {
        $clients = Client::orderBy('name')->get();
        $projectJson = fractal()->item($project, new ProjectTransformer())
            ->includeDeliverables()
            ->includeWork()
            ->toArray();
        $clientsJson = fractal()->collection($clients, new ClientTransformer())->toArray();

        return view('projects.show', [
            'project' => $project,
            'json'    => [
                'project' => $projectJson,
                'clients' => $clientsJson,
            ],
        ]);
    }
}
