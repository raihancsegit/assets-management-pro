<?php

namespace App\Services;

use App\Filters\CommonFilter;
use App\Models\Deposit;
use App\Models\Expanse;
use App\Models\Income;
use Illuminate\Http\Request;

class CommonFilterService
{
    public function dashboardDeposits(Deposit $model, Request $request)
    {
        $model = $model::with([
            'category',
            'type',
            'unit',
        ])->latest('id')->take(30);

        $hasAppliedFilters = $request->has('custom_action') && $request->custom_action === 'depositFilter';

        return $this->applyFilters(
            $model,
            $hasAppliedFilters ? $request->query() : []
        );
    }

    public function dashboardExpanses(Expanse $model, Request $request)
    {
        $model = $model::with([
            'category',
            'type',
            'unit',
        ])->latest('id')->take(30);

        $hasAppliedFilters = $request->has('custom_action') && $request->custom_action === 'expanseFilter';

        return $this->applyFilters(
            $model,
            $hasAppliedFilters ? $request->query() : []
        );
    }

    public function dashboardIncomes(Income $model, Request $request)
    {
        $model = $model::with([
            'category',
            'type',
            'unit',
        ])->latest('id')->take(30);

        $hasAppliedFilters = $request->has('custom_action') && $request->custom_action === 'incomeFilter';

        return $this->applyFilters(
            $model,
            $hasAppliedFilters ? $request->query() : []
        );
    }

    public function expanses(Expanse $model, Request $request, int $perPage)
    {
        $model = $model::with([
            'category',
            'type',
            'unit',
        ])->latest('id')->take($perPage);

        $hasAppliedFilters = $request->has('custom_action') && $request->custom_action === 'singleExpanseFilter';

        return $this->applyFilters(
            $model,
            $hasAppliedFilters ? $request->query() : []
        );
    }

    public function deposits(Deposit $model, Request $request, int $perPage)
    {
        $model = $model::with([
            'category',
            'type',
            'unit',
        ])->latest('id')->take($perPage);

        $hasAppliedFilters = $request->has('custom_action') && $request->custom_action === 'singleDepositFilter';

        return $this->applyFilters(
            $model,
            $hasAppliedFilters ? $request->query() : []
        );
    }

    public function inreviewExpanseFilter(Expanse $model, Request $request, int $perPage)
    {
        $model = $model::with([
            'category',
            'type',
            'unit',
        ])->latest('id')->where('status', 0)->take($perPage);

        $hasAppliedFilters = $request->has('custom_action') && $request->custom_action === 'inreviewExpanseFilter';

        return $this->applyFilters(
            $model,
            $hasAppliedFilters ? $request->query() : []
        );
    }

    public function inreviewIncomeFilter(Income $model, Request $request, int $perPage)
    {
        $model = $model::with([
            'category',
            'type',
            'unit',
        ])->latest('id')->where('status', 0)->take($perPage);

        $hasAppliedFilters = $request->has('custom_action') && $request->custom_action === 'inreviewIncomeFilter';

        return $this->applyFilters(
            $model,
            $hasAppliedFilters ? $request->query() : []
        );
    }

    protected function applyFilters($model, $query)
    {
        return resolve(CommonFilter::class)->getResults([
            'model' => $model,
            'params' => $query,
        ])->get();
    }
}
