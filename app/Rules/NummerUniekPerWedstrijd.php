<?php

namespace App\Rules;

use App\Models\Reeks;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NummerUniekPerWedstrijd implements Rule
{
    private $westrijd_id;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($wedstrijd_id)
    {
        $this->westrijd_id = $wedstrijd_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            Reeks::where(
                [
                    ["wedstrijd_id", "=", $this->westrijd_id],
                    ["nummer", "=", $value]
                ]
            )->firstOrFail();
            return false;
        } catch (ModelNotFoundException $modelNotFoundException) {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.nummer_uniek_per_wedstrijd');
    }
}
