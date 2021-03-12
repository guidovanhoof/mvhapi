<?php

namespace App\Rules;

use App\Models\GetrokkenMaat;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetrokkenMaatUniekPerWedstrijddeelnemer implements Rule
{
    private $wedstrijddeelnemer_id;
    private $getrokkenmaat_id;

    /**
     * Create a new rule instance.
     *
     * @param $wedstrijddeelnemer_id
     * @param $getrokkenmaat_id
     */
    public function __construct($wedstrijddeelnemer_id, $getrokkenmaat_id = null)
    {
        $this->wedstrijddeelnemer_id = $wedstrijddeelnemer_id;
        $this->getrokkenmaat_id = $getrokkenmaat_id;
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
            $getrokkenMaat = GetrokkenMaat::where(
                [
                    ["wedstrijddeelnemer_id", "=", $this->wedstrijddeelnemer_id],
                    ["getrokken_maat_id", "=", $value],
                ]
            )->firstOrFail();
            if ($getrokkenMaat->id == $this->getrokkenmaat_id) {
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
        return trans('validation.getrokkenmaat_uniek_per_wedstrijddeelnemer');
    }
}
