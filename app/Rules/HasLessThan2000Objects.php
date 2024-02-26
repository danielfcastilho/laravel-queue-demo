<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasLessThan2000Objects implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        static $objectsCount = 0;
        $objectsCount++;
        if ($objectsCount > 2000) {
            $fail('The request may not contain more than 2000 objects.');
        }
    }
}
