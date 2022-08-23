<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimulationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'simulation_metadata' => ['array', 'required'],
            'extra.sinks' => ['array','required'],
            'extra.sources' => ['array','required']
        ];
    }
}
