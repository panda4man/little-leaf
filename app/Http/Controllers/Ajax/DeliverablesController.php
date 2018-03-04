<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Requests\CompleteDeliverableRequest;
use App\Http\Requests\CreateDeliverableRequest;
use App\Models\Deliverable;
use App\Models\Project;
use App\Repositories\DeliverableRepository;
use App\Transformers\DeliverableTransformer;
use App\Http\Controllers\Controller;

class DeliverablesController extends Controller
{
    /**
     * @param CreateDeliverableRequest $req
     * @param DeliverableRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateDeliverableRequest $req, DeliverableRepository $repo)
    {
        $project = null;

        if($req->has('project_id')) {
            $project = Project::find($req->get('project_id'));
        }

        $deliverable = $repo->create($req->all(), $project);

        if($deliverable) {
            $deliverable = fractal()->item($deliverable, new DeliverableTransformer())->toArray();

            return response()->json([
                'success' => true,
                'data'    => $deliverable,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }

    /**
     * @param Deliverable $deliverable
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Deliverable $deliverable)
    {
        $deliverable = fractal()->item($deliverable, new Deliverable())->toArray();

        return response()->json([
            'success' => true,
            'data'    => $deliverable,
        ]);
    }

    /**
     * @param CreateDeliverableRequest $req
     * @param DeliverableRepository $repo
     * @param Deliverable $deliverable
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CreateDeliverableRequest $req, DeliverableRepository $repo, Deliverable $deliverable)
    {
        $project = null;

        if($req->has('project_id')) {
            $project = Project::find($req->get('project_id'));
        }

        $success = $repo->update($deliverable, $req->all(), $project);

        if($success) {
            $deliverable = Deliverable::find($deliverable->id);
            $deliverable = fractal()->item($deliverable, new DeliverableTransformer())->toArray();

            return response()->json([
                'data' => $deliverable,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }

    /**
     * @param Deliverable $deliverable
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Deliverable $deliverable)
    {
        $success = false;

        try {
            $success = $deliverable->delete();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        if($success) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }

    /**
     * @param Deliverable $deliverable
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWorkDone(Deliverable $deliverable)
    {
        $totalHours = $deliverable->work->sum('hours');

        return response()->json([
            'success' => true,
            'data'    => [
                'hours' => $totalHours,
            ],
        ]);
    }

    /**
     * @param CompleteDeliverableRequest $req
     * @param DeliverableRepository $repo
     * @param Deliverable $deliverable
     * @return \Illuminate\Http\JsonResponse
     */
    public function postComplete(CompleteDeliverableRequest $req, DeliverableRepository $repo, Deliverable $deliverable)
    {
        $success = $repo->update($deliverable, $req->all());

        if($success) {
            $deliverable = Deliverable::find($deliverable->id);
            $deliverable = fractal()->item($deliverable, new DeliverableTransformer())->toArray();

            return response()->json([
                'success' => true,
                'data'    => $deliverable,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }
}
