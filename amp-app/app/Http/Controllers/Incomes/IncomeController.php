<?php

namespace App\Http\Controllers\Incomes;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\Category;
use App\Models\Income;
use App\Models\Type;
use App\Models\Unit;
use DateTime;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incomes = Income::with([
            'category',
            'type',
            'unit',
        ])->latest('id')->paginate(10);

        $route = 'incomes.index';

        return view('admin.incomes.index', compact('incomes', 'route'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('incomes.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomeRequest $request)
    {
        $attr = [
            'status' => 1,
            'created_by' => Auth()->user()->id,
        ];
        $attr = array_merge($attr, $request->toArray());

        (new Income($attr))->save();

        $msg = 'Success: Income has been added successfully';

        if ($request->has('category_page')) {
            return redirect()->route('categories.incomes.index', $request->category_id)->with('success', $msg);
        }

        return redirect()->route('incomes.index')->with('success', $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        $categories = Category::get(['id', 'name']);
        $types = Type::get(['id', 'name']);
        $units = Unit::get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.incomes.edit', compact(
            'income',
            'categories',
            'types',
            'units',
            'route'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Income $income)
    {
        $categories = Category::get(['id', 'name']);
        $types = Type::get(['id', 'name']);
        $units = Unit::get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.incomes.edit', compact(
            'income',
            'categories',
            'types',
            'units',
            'route'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomeRequest $request, Income $income)
    {
        // todo: code duplicate on income update
        $income->date = $request->has('date') ? new DateTime($request->date) : new DateTime();
        $income->amount = $request->amount;
        $income->receipt_no = $request->receipt_no;
        $income->unit_value = $request->unit_value;
        $income->details = $request->details;
        $income->notes = $request->notes;
        $income->updated_by = Auth()->user()->id;
        $income->status = auth()->user()->hasRole('manager') ? 0 : $request->status;

        if ($request->has('category_id')) {
            $income->category_id = $request->category_id !== 'Choose category...' ? $request->category_id : null;
        }
        if ($request->has('type_id')) {
            $income->type_id = $request->type_id !== 'Choose type...' ? $request->type_id : null;
        }
        if ($request->has('unit_id')) {
            $income->unit_id = $request->unit_id !== 'Choose unit...' ? $request->unit_id : null;
        }

        $income->save();

        $msg = 'Success: Income has been successfully updated';

        if (request()->query('route')) {
            return redirect()->route(request()->query('route'))->with('success', $msg);
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        $income->delete();

        $route = request()->query('route') ?: 'incomes.index';

        return redirect()->route($route)->with(['success' => 'Success: '.__('Income amount').' '.$income->amount.' has been deleted successfuly']);
    }
}
