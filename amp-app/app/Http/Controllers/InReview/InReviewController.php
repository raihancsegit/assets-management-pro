<?php

namespace App\Http\Controllers\InReview;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Expanse;
use App\Models\Income;
use App\Services\CommonFilterService;
use Illuminate\Http\Request;

class InReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CommonFilterService $filter, Expanse $expanse, Income $income)
    {

        $income = $filter->inreviewIncomeFilter(
            $income,
            $request,
            30
        );

        $expanses = $filter->inreviewExpanseFilter(
            $expanse,
            $request,
            30
        );

        $route = 'inReview';

        $categories = Category::get();

        return view('admin.InReview.index', compact(
            'income',
            'expanses',
            'route',
            'categories'
        ));
    }
}
