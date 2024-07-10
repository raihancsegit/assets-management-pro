<?php

namespace App\Http\Controllers\Productions;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductionRequest;
use App\Http\Requests\UpdateProductionRequest;
use App\Models\Location;
use App\Models\MilkProduction;
use App\Models\MilkProductionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MilkProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:staff|admin'], ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = $request->query('date');
        $query = MilkProduction::where('category_id', $this->getProductionCategoryId());
        $locations = Location::all();

        if ($request->query('date')) {
            $dateTime = date('Y-m-d', strtotime($date));
            $query = $query->whereDate('date', $dateTime);
        }
        $productions = $query->latest('date')->paginate(10);

        return view('admin.productions.index', compact(
            'productions',
            'locations'
        ));
    }

    public function milkProductionReport(Request $request)
    {
        // Check for the custom action
        if ($request->has('custom_action') && $request->custom_action === 'milkProductionReport') {
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            // Set a default date range if not provided
            $start_date = $start_date ? $start_date : date('Y-m-d', strtotime('-31 days'));
            $end_date = $end_date ? $end_date : date('Y-m-d');

            // Query with date range filter
            $reports = DB::table('milk_productions')
                ->select(DB::raw('DATE_FORMAT(date, "%d %M, %Y") as date'))
                ->addSelect(DB::raw('sum(case when category_id = 1 then quantity else 0 end) as production'))
                ->addSelect(DB::raw('sum(case when category_id = 2 then quantity else 0 end) as sell'))
                ->addSelect(DB::raw('GROUP_CONCAT(sell_price) as sell_price'))
                ->addSelect(DB::raw('sum(case when category_id = 2 then sell_amount else 0 end) as sell_amount'))
                ->addSelect(DB::raw('GROUP_CONCAT(locations.name) as location'))
                ->leftJoin('locations', 'milk_productions.location_id', '=', 'locations.id')
                ->whereDate('date', '>=', $start_date)
                ->whereDate('date', '<=', $end_date)
                ->groupBy('date')
                ->paginate(30);

            return view('admin.productions.reports', compact('reports'));
        } else {
            // Default query for the last 30 days
            $thirty_days_ago = date('Y-m-d', strtotime('-31 days'));

            $reports = DB::table('milk_productions')
                ->select(DB::raw('DATE_FORMAT(date, "%d %M, %Y") as date'))
                ->addSelect(DB::raw('sum(case when category_id = 1 then quantity else 0 end) as production'))
                ->addSelect(DB::raw('sum(case when category_id = 2 then quantity else 0 end) as sell'))
                ->addSelect(DB::raw('GROUP_CONCAT(sell_price) as sell_price'))
                ->addSelect(DB::raw('sum(case when category_id = 2 then sell_amount else 0 end) as sell_amount'))
                ->addSelect(DB::raw('GROUP_CONCAT(locations.name) as location'))
                ->leftJoin('locations', 'milk_productions.location_id', '=', 'locations.id')
                ->whereDate('date', '>=', $thirty_days_ago)
                ->groupBy('date')
                ->paginate(30);

            return view('admin.productions.reports', compact('reports'));
        }
    }

    private function getProductionCategoryId()
    {
        $mk = MilkProductionCategory::where('name', 'production')->first();
        if (! $mk) {
            return 0;
        }

        return $mk->id;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductionRequest $request)
    {
        $production = (new MilkProduction($request->toArray()));
        $production->category_id = $this->getProductionCategoryId();
        $production->save();

        return redirect()->route('productions.index')->with('success', 'Success: '.$request->name.' added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(MilkProduction $production)
    {
        $locations = Location::all();

        return view('admin.productions.edit', compact('production', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductionRequest $request, MilkProduction $production)
    {
        ($production->fill($request->toArray()))->save();

        return back()->with('success', 'Success: '.$request->name.' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MilkProduction $production)
    {
        $production->delete();

        return redirect()->route('productions.index')->with(['success' => 'Success: '.$production->name.' has been deleted successfuly']);
    }
}
