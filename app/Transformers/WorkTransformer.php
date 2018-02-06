<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class WorkTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = ['project', 'task'];

    /**
     * @var array
     */
    protected $defaultIncludes = ['project', 'task'];

    /**
     * A Fractal transformer.
     *
     * @param $work
     * @return array
     */
    public function transform($work)
    {
        $data = null;

        if(!$work)
            return $data;

        $data = [
            'id'          => $work->id,
            'description' => $work->description,
            'hours'       => $work->hours,
            'day'         => $work->created_at->format('Y-m-d'),
        ];

        return $data;
    }

    public function project($work)
    {
        if(!$work)
            return null;

        $work->load('project');

        return $this->item($work->project, new ProjectTransformer());
    }

    public function task($work)
    {
        if(!$work)
            return null;

        $work->load('task');

        return $this->item($work->task, new TaskTransformer());
    }
}
