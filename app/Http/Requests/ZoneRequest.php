<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZoneRequest extends FormRequest
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
        $rules = [
            'name' => ['required'],
            'status' => ['required'],
            'parent_zone_id' => ['nullable', 'exists:zones,id'],
        ];

        // If it's a sub-zone (has parent_zone_id), require area_id
        // Otherwise, require division_id
        if ($this->input('parent_zone_id')) {
            $rules['area_id'] = ['required', 'array', 'min:1'];
            $rules['area_id.*'] = ['exists:areas,id'];
        } else {
            $rules['division_id'] = ['required', 'array', 'min:1'];
            $rules['division_id.*'] = ['exists:divisions,id'];
        }

        return $rules;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ?? 1
        ]);
    }
}
