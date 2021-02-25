<?php

namespace App\Rules;

use App\Models\Plaatsdeelnemer;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeelnemerUniekPerPlaats implements Rule
{
    private $plaats_id;
    private $plaatsdeelnemer_id;

    /**
     * Create a new rule instance.
     *
     * @param $plaats_id
     * @param $plaatsdeelnemer_id
     */
    public function __construct($plaats_id, $plaatsdeelnemer_id = null)
    {
        $this->plaats_id = $plaats_id;
        $this->plaatsdeelnemer_id = $plaatsdeelnemer_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            $plaatsdeelnemer = Plaatsdeelnemer::where(
                [
                    ["plaats_id", "=", $this->plaats_id],
                    ["wedstrijddeelnemer_id", "=", $value],
                ]
            )->firstOrFail();
            if ($plaatsdeelnemer->id == $this->plaatsdeelnemer_id) {
                return true;
            }
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
    public function message(): string
    {
        return trans('validation.deelnemer_uniek_per_plaats');
    }
}
