<?php

namespace App\Rules;

use App\Models\DemoTest;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IsActiveRef implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $ref, Closure $fail): void
    {
        if (DemoTest::inactiveRef($ref)->exists()) {
            $fail("The $attribute with reference $ref is inactive.");
        }
    }
}
