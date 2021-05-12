<?php

namespace App\Http\Requests\Embers;

use App\Models\Instance;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSinkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->can('create', Instance::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // TODO: revisit when equipments structure is known
            // 'equipments' => ['required', 'array'],
            // 'equipments.*' => ['required', 'string', 'exists:categories,id'],
            'template_id' => ['required', 'string', 'exists:templates,id'],
            'location_id' => ['required', 'string', 'exists:locations,id'],
        ];
    }
}
