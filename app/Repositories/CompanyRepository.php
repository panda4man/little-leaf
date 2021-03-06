<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\User;

class CompanyRepository implements iResourceRepository
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
            $company = Company::create($fields);
            $count = Company::count();

            // If first, then make default
            if($count < 2) {
                $company->default = true;
            }

            // Set belongsTo's
            foreach($relations as $rel) {
                if($rel instanceof User) {
                    $company->owner()->associate($rel);
                }
            }

            // save data thus far
            $company->save();

            // if making default set all other companies as not default
            if($count > 1 && $company->owner && $company->default) {
                $company->owner->companies()->where('id', '<>', $company->id)->update(['default' => 0]);
            }

            return $company;
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
        return Company::find($id);
    }

    /**
     * Update a resource
     *
     * @param mixed $identifier
     * @param array $fields
     * @param array $relations
     * @return bool
     */
    public function update($identifier, array $fields = [], ...$relations): bool
    {
        $success = false;
        $company = null;

        if($identifier instanceof Company) {
            $company = $identifier;
        } else {
            $company = Company::find($identifier);
        }

        if(!$company) {
            return $success;
        }

        $success = $company->update($fields);

        if($success) {
            foreach($relations as $rel) {
                if($rel instanceof User) {
                    $company->owner()->associate($rel);
                }
            }

            $company->save();
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
        $company = $this->find($id);

        if($company) {
            return $company->delete();
        }

        return true;
    }
}