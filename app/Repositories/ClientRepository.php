<?php


namespace App\Repositories;


use App\Models\Client;
use App\Models\Company;

class ClientRepository implements iResourceRepository
{
    /**
     * Create a new resource
     *
     * @param array $fields
     * @param array $relations
     * @return mixed
     */
    public function create(array $fields = [], ...$relations)
    {
        try {
            $client = Client::create($fields);

            foreach($relations as $rel) {
                if($rel instanceof Company) {
                    $client->company()->associate($rel);
                }
            }

            $client->save();

            return $client;
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return null;
        }
    }

    /**
     * Find a resource
     *
     * @param integer $id
     * @return mixed
     */
    public function find(int $id)
    {
        return Client::find($id);
    }

    /**
     * Update a resource
     *
     * @param integer $id
     * @param array $fields
     * @return bool
     */
    public function update(int $id, array $fields = [])
    {
        $success = false;
        $client = $this->find($id);

        if(!$client) {
            return $success;
        }

        try {
            $success = $client->update($fields);
        } catch(\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $success;
    }

    /**
     * Delete a resource
     *
     * @param integer $id
     * @return bool
     */
    public function delete(int $id)
    {
        $client = $this->find($id);

        if($client) {
            return $client->delete();
        }

        return true;
    }
}