<?php

namespace App\Rules;

use App\Models\Reeks;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NummerUniekPerWedstrijd implements Rule
{
    private $westrijd_id;
    private $reeks_id;

    /**
     * Create a new rule instance.
     *
     * @param $wedstrijd_id
     * @param null $reeks_id
     */
    public function __construct($wedstrijd_id, $reeks_id = null)
    {
        $this->westrijd_id = $wedstrijd_id;
        $this->reeks_id = $reeks_id;
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
            $reeks = Reeks::where(
                $this->getWhereCriteria($value)
            )->firstOrFail();
            if ($reeks->id == $this->reeks_id) {
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
        return trans('validation.nummer_uniek_per_wedstrijd');
    }

    /**
     * @param $value
     * @return array[]
     */
    private function getWhereCriteria($value): array
    {
        $whereUnique = [
            ["wedstrijd_id", "=", $this->westrijd_id],
            ["nummer", "=", $value],
            //$this->reeks_id ? ["id", "<>", $this->reeks_id] : [],
        ];
        return $whereUnique;
    }
}
