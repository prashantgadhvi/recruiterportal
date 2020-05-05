<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployerRequest extends FormRequest
{
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
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'business_name' => 'sometimes|max:255',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'country_id' => 'required|exists:countries,id',
            'industry_id' => 'required|exists:industries,id',
            'phone' => 'sometimes|max:255',
            'messenger_type' => 'sometimes|max:255',
            'messenger_id' => 'sometimes|max:255',
            'email' => 'required|email',
            'status_id' => 'required|exists:statuses,id',
            'requisition_id' => 'required|exists:requisitions,id',
        ];
    }
}
