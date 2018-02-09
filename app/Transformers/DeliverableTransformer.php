<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class DeliverableTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param $deliverable
     * @return array
     */
    public function transform($deliverable)
    {
        $data = null;

        if(!$deliverable) {
            return $data;
        }

        $data = [
            'id'              => $deliverable->id,
            'name'            => $deliverable->name,
            'description'     => $deliverable->description,
            'estimated_cost'  => $deliverable->estimated_cost,
            'estimated_hours' => $deliverable->estimated_hours,
            'due_at'          => $deliverable->due_at ? $deliverable->due_at->format('Y-m-d') : null,
            'completed_at'    => $deliverable->completed_at ? $deliverable->completed_at->format('Y-m-d') : null,
        ];

        return $data;
    }
}
