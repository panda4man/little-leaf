<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['work'];

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
            'id'   => $task->id,
            'name' => $task->name,
        ];

        return $data;
    }

    public function work($task)
    {
        if(!$task)
            return null;

        $task->load('work');

        return $this->item($task->work, new WorkTransformer());
    }
}
