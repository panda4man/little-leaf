<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['work', 'projects'];

    /**
     * A Fractal transformer.
     *
     * @param $task
     * @return array
     */
    public function transform($task)
    {
        $data = null;

        if(!$task)
            return $data;

        $data = [
            'id'          => $task->id,
            'name'        => $task->name,
            'description' => $task->description,
        ];

        return $data;
    }

    /**
     * @param $task
     * @return \League\Fractal\Resource\Collection|\League\Fractal\Resource\NullResource
     */
    public function includeProjects($task)
    {
        if(!$task)
            return $this->null();

        $task->load('projects');

        return $this->collection($task->projects, new ProjectTransformer());
    }

    /**
     * @param $task
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeWork($task)
    {
        if(!$task)
            return $this->null();

        $task->load('work');

        return $this->item($task->work, new WorkTransformer());
    }
}
