<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\InvokableRule;

class MaxSizeDisk implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        /** @var User $user */
        $user = auth()->user();
        $size = $value->getSize();
        if ($user->getMaxSize() < $size) {
            $fail('Не хватает памяти в облачном хранилище, загрузить файл невозможно');
        }
    }
}
