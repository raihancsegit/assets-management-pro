<?php

namespace App\Http\Controllers\Units;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Category;
use App\Models\Scheme;
use App\Models\Unit;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:staff|admin'], ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::with(['scheme'])
            ->latest('id')
            ->paginate(10);

        $categories = Category::all();
        $schemes = Scheme::all();

        return view('admin.units.index', compact(
            'units',
            'categories',
            'schemes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        (new Unit($request->toArray()))->save();

        return redirect()->route('units.index')->with('success', 'Success: '.$request->name.' added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        $categories = Category::all();
        $schemes = Scheme::all();

        return view('admin.units.edit', compact('unit', 'categories', 'schemes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        ($unit->fill($request->toArray()))->save();

        return back()->with('success', 'Success: '.$request->name.' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('units.index')->with(['success' => 'Success: '.$unit->name.' has been deleted successfuly']);
    }
}
