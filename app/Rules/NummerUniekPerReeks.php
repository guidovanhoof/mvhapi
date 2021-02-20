<?php

namespace App\Rules;

use App\Models\Plaats;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NummerUniekPerReeks implements Rule
{
    private $reeks_id;
    private $plaats_id;

    /**
     * Create a new rule instance.
     *
     * @param $reeks_id
     * @param null $plaats_id
     */
    public function __construct($reeks_id, $plaats_id = null)
    {
        $this->reeks_id = $reeks_id;
        $this->plaats_id = $plaats_id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {
            $plaats = Plaats::where(
                [
                    ["reeks_id", "=", $this->reeks_id],
                    ["nummer", "=", $value],
                ]
            )->firstOrFail();
            if ($plaats->id == $this->plaats_id) {
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
        return trans('validation.nummer_uniek_per_reeks');
    }
}
