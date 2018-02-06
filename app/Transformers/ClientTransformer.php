<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param $client
     * @return array
     */
    public function transform($client)
    {
        $data = null;

        if(!$client) {
            return $data;
        }

        $data = [
            'id'      => $client->id,
            'name'    => $client->name,
            'address' => $client->address,
            'city'    => $client->city,
            'state'   => $client->state,
            'zip'     => $client->zip,
            'country' => $client->country,
        ];

        return $data;
    }
}
