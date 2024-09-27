<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExcelRule implements ValidationRule
{
    public function passes($attribute, $value)
    {
        // Check if the value is an instance of UploadedFile
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            $extension = strtolower($value->getClientOriginalExtension());

            return in_array($extension, ['csv', 'xls', 'xlsx']);
        }

        return false; // Return false if the value is not an UploadedFile instance
    }

    public function message()
    {
        return 'The uploaded file must be of type: csv, xls, or xlsx.';
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // This method is not needed as you already have passes() for validation.
    }
}
