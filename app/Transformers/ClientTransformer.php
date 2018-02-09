<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = ['company'];

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
            'hash_id' => $client->hash_id,
            'name'    => $client->name,
            'address' => $client->address,
            'city'    => $client->city,
            'state'   => $client->state,
            'zip'     => $client->zip,
            'country' => $client->country,
        ];

        return $data;
    }

    /**
     * @param $client
     * @return \League\Fractal\Resource\Item|array
     */
    public function includeCompany($client)
    {
        if(!$client)
            return [];

        $client->load('company');

        return $this->item($client->company, new CompanyTransformer());
    }
}
