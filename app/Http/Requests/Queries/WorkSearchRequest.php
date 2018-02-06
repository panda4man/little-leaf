<?php

namespace App\Http\Request\Queries;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class WorkSearchRequest extends FormRequest
{
    public $allowedParams = [
        'date',
        'client',
        'project',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'date_format:Y-m-d'
        ];
    }

    /**
     * @param string $date
     * @return string|static
     */
    public function dateTransform($date = '')
    {
        $date = Carbon::createFromFormat('Y-m-d', $date);

        return $date;
    }
}
