<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ProjectTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param $project
     * @return array
     */
    public function transform($project)
    {
        $data = null;

        if(!$project)
            return $data;

        $data = [
            'id'        => $project->id,
            'name'      => $project->name,
            'completed_at' => $project->completed_at->format('Y-m-d'),
        ];

        return $data;
    }
}
