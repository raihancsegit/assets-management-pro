<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CategoryReportsDownloadController extends Controller
{
    protected $reportService;

    public function __construct(CategoryReportService $reportService)
    {
        $this->middleware(['role:staff|admin']);
        $this->reportService = $reportService;
    }

    public function index(Category $category, Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $data = $this->reportService->getDateWiseReportWithFilter($category->id, $startDate, $endDate);
        } else {
            $data = $this->reportService->getDateWiseReport($category->id);
        }

        $pdf = PDF::loadView('admin.categories.reports-download', compact('data', 'category'));

        return $pdf->stream('reports-'.date('Y-m-d').'.pdf');

    }
}
