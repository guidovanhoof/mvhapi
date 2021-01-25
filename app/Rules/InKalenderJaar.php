<?php

namespace App\Rules;

use App\Models\Kalender;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InKalenderJaar implements Rule
{
    private $kalenderId;

    /**
     * Create a new rule instance.
     *
     * @param $kalenderId
     */
    public function __construct($kalenderId)
    {
        $this->kalenderId = $kalenderId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $kalender = Kalender::findorFail($this->kalenderId);
            return $kalender->jaar == Carbon::parse($value)->year;

        } catch (ModelNotFoundException $modelNotFoundException) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.inkalenderjaar');
    }
}
