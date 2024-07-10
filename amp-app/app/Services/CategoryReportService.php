<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class CategoryReportService
{
    public function getSummaryReport($categoryId)
    {
        return DB::select(
            '
                SELECT
                    dep.totalDeposits,
                    COALESCE(exp.totalExpanses, 0) as totalExpanses,
                    COALESCE(inc.totalIncomes, 0) as totalIncomes,
                    ((dep.totalDeposits - COALESCE(exp.totalExpanses, 0)) + COALESCE(inc.totalIncomes, 0)) as totalBalances
                FROM (
                    SELECT
                        d.category_id,
                        SUM(d.amount) as totalDeposits
                    FROM deposits d
                    WHERE d.category_id = :deposit_category
                    AND d.status = 1
                    GROUP BY d.category_id
                ) as dep
                LEFT JOIN (
                    SELECT
                        e.category_id,
                        SUM(e.amount) as totalExpanses
                    FROM expanses e
                    WHERE e.category_id = :expanse_category
                    AND e.status = 1
                    GROUP BY e.category_id
                ) as exp ON exp.category_id = dep.category_id
                LEFT JOIN (
                    SELECT
                        i.category_id,
                        SUM(i.amount) as totalIncomes
                    FROM incomes i
                    WHERE i.category_id = :income_category
                    AND i.status = 1
                    GROUP BY i.category_id
                ) as inc ON inc.category_id = dep.category_id
            ',
            [
                'deposit_category' => $categoryId,
                'expanse_category' => $categoryId,
                'income_category' => $categoryId,
            ]
        );
    }

    private function getQuery()
    {
        return "
            SELECT
                date,
                SUM(CASE WHEN transaction_type = 'deposit' THEN amount ELSE 0 END) AS deposits,
                SUM(CASE WHEN transaction_type = 'expanse' THEN amount ELSE 0 END) AS expanses,
                SUM(CASE WHEN transaction_type = 'income' THEN amount ELSE 0 END) AS incomes
            FROM (
                select
                    d.`date`,
                    d.amount,
                    'deposit' AS transaction_type
                from deposits d
                WHERE d.status = 1
                AND d.category_id = :deposit_category
                UNION ALL
                select
                    e.`date`,
                    e.amount,
                    'expanse' AS transaction_type
                from expanses e
                WHERE e.status = 1
                AND e.category_id = :expanse_category
                UNION ALL
                select
                    i.`date`,
                    i.amount,
                    'income' AS transaction_type
                from incomes i
                WHERE i.status = 1
                AND i.category_id = :income_category
            ) AS combined_data
        ";
    }

    public function getDateWiseReport($categoryId)
    {
        $query = DB::select(
            $this->getQuery().'
                GROUP BY date
                ORDER BY date desc
            ',
            [
                'deposit_category' => $categoryId,
                'expanse_category' => $categoryId,
                'income_category' => $categoryId,
            ]
        );

        return $this->paginate($query, 10);
    }

    public function getDateWiseReportWithFilter($categoryId, $start_date, $end_date)
    {
        $query = DB::select(
            $this->getQuery().'
            WHERE combined_data.date BETWEEN :start_date AND :end_date
            GROUP BY date
            ORDER BY date DESC
        ',
            [
                'deposit_category' => $categoryId,
                'expanse_category' => $categoryId,
                'income_category' => $categoryId,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        );

        return $this->paginate($query, 10);
    }

    /**
     * Pagination
     */
    protected function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
