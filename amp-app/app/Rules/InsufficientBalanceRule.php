<?php

namespace App\Rules;

use App\Models\Deposit;
use App\Models\Expanse;
use App\Models\Income;
use Illuminate\Contracts\Validation\Rule;

class InsufficientBalanceRule implements Rule
{
    public function passes($attribute, $value)
    {
        $totalDeposits = Deposit::where('status', 1)->sum('amount');
        $totalExpenses = Expanse::where('status', 1)->sum('amount');
        $totalIncomes = Income::where('status', 1)->sum('amount');
        $totalBalance = $totalDeposits - $totalExpenses + $totalIncomes;

        return $totalBalance >= $value;
    }

    public function message()
    {
        return __('Error: Insufficient balance.');
    }
}
