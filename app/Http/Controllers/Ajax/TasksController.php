<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Transformers\TaskTransformer;

class TasksController extends Controller
{
    /**
     * @param CreateTaskRequest $req
     * @param TaskRepository $repo
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateTaskRequest $req, TaskRepository $repo)
    {
        $user = auth()->user();
        $task = $repo->create($req->except('projects'), $user);

        if($task) {
            if($req->has('projects') && count($req->get('projects'))) {
                $projects = Project::whereIn('id', $req->get('projects'))->get();
                $validIds = $projects->pluck('id')->all();

                $task->projects()->sync($validIds);
            }

            $task = $repo->find($task->id);
            $task = fractal()->item($task, new TaskTransformer())->includeProjects()->toArray();

            return response()->json([
                'success' => true,
                'data'    => $task,
            ], 203);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }

    /**
     * @param CreateTaskRequest $req
     * @param TaskRepository $repo
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CreateTaskRequest $req, TaskRepository $repo, Task $task)
    {
        $task = $repo->update($task->id, $req->except('projects'));

        if($task) {
            if($req->has('projects') && count($req->get('projects'))) {
                $projects = Project::whereIn('id', $req->get('projects'))->get();
                $validIds = $projects->pluck('id')->all();

                $task->projects()->sync($validIds);
            }

            $task = $repo->find($task->id);
            $task = fractal()->item($task, new TaskTransformer())->includeProjects()->toArray();

            return response()->json([
                'success' => true,
                'data'    => $task,
            ]);
        }

        return response()->json([
            'success' => false,
        ], 400);
    }

    /**
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Task $task)
    {
        $success = false;

        try {
            if($task->delete()) {
                $success = true;

                return response()->json([
                    'success' => true
                ]);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        if(!$success) {
            return response()->json([
                'success' => false
            ]);
        }
    }
}
