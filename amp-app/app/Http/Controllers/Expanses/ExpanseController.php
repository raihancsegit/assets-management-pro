<?php

namespace App\Http\Controllers\Expanses;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpanseRequest;
use App\Http\Requests\UpdateExpanseRequest;
use App\Models\Category;
use App\Models\Expanse;
use App\Models\Type;
use App\Models\Unit;
use App\Services\CommonFilterService;
use DateTime;
use Illuminate\Http\Request;

class ExpanseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CommonFilterService $filter, Expanse $expanse)
    {
        $expanses = $filter->expanses(
            $expanse,
            $request,
            20
        );

        $route = 'expanses.index';
        $categories = Category::latest('id')->get();

        return view('admin.expanses.index', compact('expanses', 'route', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('expanses.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpanseRequest $request)
    {
        $attr = [
            'status' => 1,
            'created_by' => Auth()->user()->id,
        ];
        $attr = array_merge($attr, $request->toArray());

        (new Expanse($attr))->save();

        $msg = 'Success: Expanse has been added successfully';

        if ($request->has('category_page')) {
            return redirect()->route('categories.expanses.index', $request->category_id)->with('success', $msg);
        }

        return redirect()->route('expanses.index')->with('success', $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expanse $expanse)
    {
        $categories = Category::get(['id', 'name']);
        $types = Type::get(['id', 'name']);
        $units = Unit::get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.expanses.edit', compact(
            'expanse',
            'categories',
            'types',
            'units',
            'route'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expanse $expanse)
    {
        $categories = Category::get(['id', 'name']);
        $types = Type::get(['id', 'name']);
        $units = Unit::get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.expanses.edit', compact(
            'expanse',
            'categories',
            'types',
            'units',
            'route'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExpanseRequest $request, Expanse $expanse)
    {
        // todo: code duplicate on expanse update
        $expanse->date = $request->has('date') ? new DateTime($request->date) : new DateTime();
        $expanse->amount = $request->amount;
        $expanse->receipt_no = $request->receipt_no;
        $expanse->unit_value = $request->unit_value;
        $expanse->details = $request->details;
        $expanse->notes = $request->notes;
        $expanse->updated_by = Auth()->user()->id;
        $expanse->status = auth()->user()->hasRole('manager') ? 0 : $request->status;

        if ($request->has('category_id')) {
            $expanse->category_id = $request->category_id !== 'Choose category...' ? $request->category_id : null;
        }
        if ($request->has('type_id')) {
            $expanse->type_id = $request->type_id !== 'Choose type...' ? $request->type_id : null;
        }
        if ($request->has('unit_id')) {
            $expanse->unit_id = $request->unit_id !== 'Choose unit...' ? $request->unit_id : null;
        }

        $expanse->save();

        $msg = 'Success: Expanse has been successfully updated';

        if (request()->query('route')) {
            return redirect()->route(request()->query('route'))->with('success', $msg);
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expanse $expanse)
    {
        $expanse->delete();

        $route = request()->query('route') ?: 'expanses.index';

        return redirect()->route($route)->with(['success' => 'Success: '.__('Expanse amount').' '.$expanse->amount.' has been deleted successfuly']);
    }
}
