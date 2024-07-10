<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\DateFilterRequest;
use App\Models\Category;
use App\Services\CategoryReportService;
use Illuminate\Http\Request;

class CategoryReportsController extends Controller
{
    protected $reportService;

    public function __construct(CategoryReportService $reportService)
    {
        $this->middleware(['role:staff|admin']);
        $this->reportService = $reportService;
    }

    public function index(Category $category, Request $request)
    {

        $summary = $this->reportService->getSummaryReport($category->id);
        $route = 'categories.reports.index';
        $dateWiseData = $this->reportService->getDateWiseReport($category->id);

        return view('admin.categories.reports', compact(
            'summary',
            'category',
            'route',
            'dateWiseData'
        ));
    }

    public function getFilterDateWiseReport(DateFilterRequest $request, Category $category)
    {

        $categoryId = $category->id;
        $summary = $this->reportService->getSummaryReport($categoryId);
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $dateWiseData = $this->reportService->getDateWiseReportWithFilter($categoryId, $start_date, $end_date);

        return view('admin.categories.reports', compact('dateWiseData', 'category', 'summary'));

    }
}
