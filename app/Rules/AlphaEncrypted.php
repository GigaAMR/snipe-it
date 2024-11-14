<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Crypt;

class AlphaEncrypted implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $attributeName = trim(preg_replace('/_+|snipeit|\d+/', ' ', $attribute));
            $decrypted = Crypt::decrypt($value);
            if (!ctype_alpha($decrypted) && !is_null($decrypted)) {
                $fail(trans('validation.custom.alpha_encrypted', ['attribute' => $attributeName]));
            }
        } catch (\Exception $e) {
            report($e);
            $fail('something went wrong.');
        }
    }
}
