<?php

namespace App\Http\Controllers\App\Http\Controllers\Ajax;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWorkRequest;
use App\Models\Work;
use App\Repositories\WorkRepository;
use App\Transformers\WorkTransformer;
use Illuminate\Database\Eloquent\Relations\Relation;

class WorkController extends Controller
{
    /**
     * @param CreateWorkRequest $req
     * @param WorkRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateWorkRequest $req, WorkRepository $repo)
    {
        $target = Relation::getMorphedModel($req->get('workable_type'));
        $target = $target::find($req->get('workable_id'));

        if(!$target) {
            return response()->json([
                'success' => false,
                'error'   => 'Could not find the project or deliverable this work is for.',
            ], 404);
        }

        $work = $repo->create($req->except('workable_id', 'workable_type'));

        // Associate the work to its target
        $target->work()->save($work);

        // Fetch updated data
        $work = fractal()->item($work, new WorkTransformer())->includeTask()->toArray();

        return response()->json([
            'success' => true,
            'data'    => $work,
        ]);
    }

    /**
     * @param Work $work
     * @param CreateWorkRequest $req
     * @param WorkRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Work $work, CreateWorkRequest $req, WorkRepository $repo)
    {
        $owner = $work->workable();
        $success = $repo->update($work->id, $req->except('workable_id', 'workable_type'));
        $work = $repo->find($work->id);

        if($success && $work) {
            // If owner is being updated...should be a rare case
            if($owner->id != $req->get('workable_id') && $req->get('workable_type') != $work->workable_type) {
                $target = Relation::getMorphedModel($req->get('workable_type'));
                $target = $target::find($req->get('workable_id'));

                // Detach from old owner
                $owner->work()->detach();

                // Save to new owner
                $target->work()->save($work);
            }

            $work = $repo->find($work->id);
            $work = fractal()->item($work, new WorkTransformer())->includeTaks()->toArray();

            return response()->json([
                'success' => true,
                'data'    => $work,
            ]);
        } else {
            return response()->json([
                'success' => false
            ], 400);
        }
    }

    /**
     * @param Work $work
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Work $work)
    {
        $success = false;

        try {
            $work->delete();
            $success = true;
        } catch (\Exception $e) {
            \Log::info('Could not delete work id: ' . $work->id);
        }

        if($success) {
            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false
        ], 400);
    }
}
