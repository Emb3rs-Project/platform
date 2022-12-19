<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class MarketStartDateRule implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $startDate = Carbon::createFromFormat('Y-m-d',$value);

        $selectedYears = request()->input('extra.input_data.platform_sets.YEAR');
        foreach ($selectedYears as $year) {
            if ($startDate->year < $year) {
               return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Market - The start date should not be smaller then the year selected for the TEO module, please verify your date';
    }
}
