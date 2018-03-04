<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteProjectRequest;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use App\Transformers\ProjectTransformer;

class ProjectsController extends Controller
{
    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Project $project)
    {
        $project = fractal()->item($project, new ProjectTransformer())
            ->parseIncludes(request()->get('includes'))
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * @param CreateProjectRequest $req
     * @param ProjectRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateProjectRequest $req, ProjectRepository $repo)
    {
        $client = Client::find($req->get('client_id'));
        $project = $repo->create($req->all(), $client);

        if($project) {
            $project = fractal()->item($project, new ProjectTransformer())
                ->includeClient()
                ->includeDeliverables()
                ->includeWork()
                ->toArray();

            return response()->json([
                'success' => true,
                'data'    => $project,
            ], 203);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }

    /**
     * @param Project $project
     * @param UpdateProjectRequest $req
     * @param ProjectRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Project $project, UpdateProjectRequest $req, ProjectRepository $repo)
    {
        $client = null;

        if($req->has('client_id')) {
            $client = Client::find($req->get('client_id'));
        }

        $success = $repo->update($project, $req->all(), $client);

        if($success) {
            $project = $repo->find($project->id);
            $project = fractal()->item($project, new ProjectTransformer())
                ->includeClient()
                ->includeWork()
                ->includeDeliverables()
                ->toArray();

            return response()->json([
                'success' => true,
                'data'    => $project,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Project $project)
    {
        try {
            if($project->delete()) {
                return response()->json([
                    'success' => true,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
            ], 400);
        }
    }

    /**
     * @param Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWorkDone(Project $project)
    {
        $totalHours = 0;
        $project->load('work', 'deliverables.work');

        foreach ($project->work as $w) {
            $totalHours += $w->hours;
        }

        foreach ($project->deliverables as $deliv) {
            foreach ($deliv->work as $w) {
                $totalHours += $w->hours;
            }
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'hours' => $totalHours,
            ],
        ]);
    }

    /**
     * @param CompleteProjectRequest $req
     * @param Project $project
     * @param ProjectRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function postComplete(CompleteProjectRequest $req, Project $project, ProjectRepository $repo)
    {
        $success = $repo->update($project, $req->all());

        if($success) {
            $project = Project::find($project->id);
            $project = fractal()->item($project, new ProjectTransformer())
                ->includeClient()
                ->includeWork()
                ->includeDeliverables()
                ->toArray();

            return response()->json([
                'success' => true,
                'data'    => $project,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }
}
