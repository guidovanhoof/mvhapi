<?php

namespace App\Rules;

use App\Models\Wedstrijddeelnemer;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeelnemerUniekPerWedstrijd implements Rule
{
    private $wedstrijd_id;
    private $wedstrijddeelnemer_id;

    /**
     * Create a new rule instance.
     *
     * @param $wedstrijd_id
     * @param null $wedstrijddeelnemer_id
     */
    public function __construct($wedstrijd_id, $wedstrijddeelnemer_id = null)
    {
        $this->wedstrijd_id = $wedstrijd_id;
        $this->wedstrijddeelnemer_id = $wedstrijddeelnemer_id;
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
            $wedstrijddeelnemer = Wedstrijddeelnemer::where(
                [
                    ["wedstrijd_id", "=", $this->wedstrijd_id],
                    ["deelnemer_id", "=", $value],
                ]
            )->firstOrFail();
            if ($wedstrijddeelnemer->id == $this->wedstrijddeelnemer_id) {
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
    public function message()
    {
        return trans('validation.deelnemer_uniek_per_wedstrijd');
    }
}
