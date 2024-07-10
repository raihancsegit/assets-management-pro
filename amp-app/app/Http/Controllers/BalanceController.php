<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Expanse;
use App\Models\Income;

class BalanceController extends Controller
{
    public function getBalance()
    {
        // Get total deposits, total expanses, total incomes
        $totalDeposits = Deposit::active()->sum('amount');
        $totalExpenses = Expanse::active()->sum('amount');
        $totalIncomes = Income::active()->sum('amount');

        // Calculate total balance
        $totalBalance = $totalDeposits - ($totalExpenses - $totalIncomes);

        // Format the balance
        $formattedBalance = number_format($totalBalance, 2);

        // Return the balance as JSON response
        return response()->json(['balance' => $formattedBalance]);
    }
}
