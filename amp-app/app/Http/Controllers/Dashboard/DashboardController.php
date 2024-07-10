<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Expanse;
use App\Models\Income;
use App\Services\CommonFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        Deposit $deposit,
        Expanse $expanse,
        Income $income,
        CommonFilterService $filter,
        Request $request
    ) {
        $dashboardData = $this->getDashboardData();
        $dashboardData['deposits'] = $filter->dashboardDeposits(
            $deposit,
            $request
        );
        $dashboardData['expanses'] = $filter->dashboardExpanses(
            $expanse,
            $request
        );

        $dashboardData['incomes'] = $filter->dashboardIncomes(
            $income,
            $request
        );

        $dashboardData['dailyOverview'] = $this->getListingData();

        return view('admin.dashboard.index', $dashboardData);
    }

    private function getDashboardData()
    {
        $route = 'dashboard';

        $totalDeposits = Deposit::active()->sum('amount');
        $totalExpanses = Expanse::active()->sum('amount');
        $totalIncomes = Income::active()->sum('amount');
        $inHand = str_replace(',', '', number_format($totalDeposits - $totalExpanses, 2));

        $categories = Category::latest('id')->get();

        return compact(
            'route',
            'totalDeposits',
            'totalExpanses',
            'totalIncomes',
            'inHand',
            'categories',
        );
    }

    private function getListingData()
    {
        $data = DB::select('
            select
                list.id,
                list.category_id,
                DATE_FORMAT(list.date, "%j") as month_index,
                DATE_FORMAT(list.date, "%d (%a) %m/%y") as day_index,
                list.type,
                c.name as category_name,
                list.details,
                list.receipt_no,
                list.amount,
                list.status
            FROM (
                select
                    *,
                    "deposit" as type
                FROM deposits d
                union
                select
                    *,
                    "expanse" as type
                FROM expanses e
                union
                select
                    *,
                    "income" as type
                FROM incomes i
            ) as list
            left join categories c on c.id = list.category_id
            WHERE list.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            order by date desc
        ');
        $result = [];
        foreach ($data as $k => $v) {
            $result[$v->month_index]['date_name'] = $v->day_index;
            $result[$v->month_index]['data'][] = $v;
        }

        return $result;
    }
}
