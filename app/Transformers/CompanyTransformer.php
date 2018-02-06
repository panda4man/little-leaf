<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{
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
            'name'    => $company->name,
            'address' => $company->address,
            'city'    => $company->city,
            'state'   => $company->state,
            'zip'     => $company->zip,
            'country' => $company->country,
            'photo'   => $company->photo,
            'default' => $company->default,
        ];

        return $data;
    }

    public function includeClients($company)
    {
        if(!$company)
            return null;

        $company->load('clients');

        return $this->collection($company->clients, new ClientTransformer());
    }
}
