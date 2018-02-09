<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Storage;

class CompanyTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = ['clients'];

    /**
     * A Fractal transformer.
     *
     * @param $company
     * @return array
     */
    public function transform($company)
    {
        $data = null;

        if(!$company) {
            return $data;
        }

        $data = [
            'id'      => $company->id,
            'hash_id' => $company->hash_id,
            'name'    => $company->name,
            'address' => $company->address,
            'city'    => $company->city,
            'state'   => $company->state,
            'zip'     => $company->zip,
            'country' => $company->country,
            'photo'   => $company->photo ? Storage::url($company->photo) : null,
            'default' => $company->default,
        ];

        return $data;
    }

    /**
     * @param $company
     * @return array|\League\Fractal\Resource\Collection
     */
    public function includeClients($company)
    {
        if(!$company)
            return [];

        $company->load('clients');

        return $this->collection($company->clients, new ClientTransformer());
    }
}
