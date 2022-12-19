<?php

namespace App\Http\Requests;

use App\Rules\MarketStartDateRule;
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
            'extra.sinks' => ['array','required_if:simulation_metadata.data.identifier,demo_simulation'],
            'extra.sources' => ['array','required_if:simulation_metadata.data.identifier,demo_simulation'],
            'extra.input_data.user.start_datetime' => [new MarketStartDateRule()]

        ];
    }
}
