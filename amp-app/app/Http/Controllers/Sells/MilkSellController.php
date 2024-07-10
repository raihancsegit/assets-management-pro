<?php

namespace App\Http\Controllers\Sells;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSellRequest;
use App\Http\Requests\UpdateSellRequest;
use App\Models\Location;
use App\Models\MilkProduction;
use App\Models\MilkProductionCategory;
use Illuminate\Http\Request;

class MilkSellController extends Controller
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
        $query = MilkProduction::where('category_id', $this->getSellCategoryId());
        $locations = Location::all();

        if ($request->query('date')) {
            $dateTime = date('Y-m-d', strtotime($date));
            $query = $query->whereDate('date', $dateTime);
        }
        $sells = $query->latest('date')->paginate(10);

        return view('admin.sells.index', compact(
            'sells',
            'locations'
        ));
    }

    private function getSellCategoryId()
    {
        $mk = MilkProductionCategory::where('name', 'sell')->first();

        if (! $mk) {
            return 0;
        }

        return $mk->id;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSellRequest $request)
    {
        $sells = (new MilkProduction($request->toArray()));
        $sells->category_id = $this->getSellCategoryId();
        $sells->sell_amount = $request->quantity * $request->sell_price;
        $sells->save();

        return redirect()->route('sells.index')->with('success', 'Success: '.$request->name.' added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(MilkProduction $sell)
    {
        $locations = Location::all();

        return view('admin.sells.edit', compact('sell', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSellRequest $request, MilkProduction $sell)
    {
        $sells = ($sell->fill($request->toArray()));
        $sells->sell_amount = $request->quantity * $request->sell_price;
        $sells->save();

        return back()->with('success', 'Success: '.$request->name.' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MilkProduction $sell)
    {
        $sell->delete();

        return redirect()->route('sells.index')->with(['success' => 'Success: '.$sell->name.' has been deleted successfuly']);
    }
}
