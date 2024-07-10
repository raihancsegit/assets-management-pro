<?php

namespace App\Http\Controllers\Deposits;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\UpdateDepositRequest;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Type;
use App\Models\Unit;
use App\Services\CommonFilterService;
use DateTime;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:staff|admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CommonFilterService $filter, Deposit $deposit)
    {

        $deposits = $filter->deposits(
            $deposit,
            $request,
            20
        );

        $route = 'deposits.index';

        $categories = Category::latest('id')->get();

        return view('admin.deposits.index', compact('deposits', 'route', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('deposits.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepositRequest $request)
    {
        $attr = [
            'status' => 1,
            'created_by' => Auth()->user()->id,
        ];
        $attr = array_merge($attr, $request->toArray());

        (new Deposit($attr))->save();

        $msg = 'Success: Deposit has been added successfully';

        if ($request->has('category_page')) {
            return redirect()->route('categories.deposits.index', $request->category_id)->with('success', $msg);
        }

        return redirect()->route('deposits.index')->with('success', $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit)
    {
        $categories = Category::get(['id', 'name']);
        $types = Type::get(['id', 'name']);
        $units = Unit::get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.deposits.edit', compact(
            'deposit',
            'categories',
            'types',
            'units',
            'route'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deposit $deposit)
    {
        $categories = Category::get(['id', 'name']);
        $types = Type::get(['id', 'name']);
        $units = Unit::get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.deposits.edit', compact(
            'deposit',
            'categories',
            'types',
            'units',
            'route'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepositRequest $request, Deposit $deposit)
    {
        // todo: code duplicate on deposit update
        $deposit->date = $request->has('date') ? new DateTime($request->date) : new DateTime();
        $deposit->amount = $request->amount;
        $deposit->receipt_no = $request->receipt_no;
        $deposit->unit_value = $request->unit_value;
        $deposit->details = $request->details;
        $deposit->notes = $request->notes;
        $deposit->updated_by = Auth()->user()->id;
        $deposit->status = auth()->user()->hasRole('manager') ? 0 : $request->status;

        if ($request->has('category_id')) {
            $deposit->category_id = $request->category_id !== 'Choose category...' ? $request->category_id : null;
        }
        if ($request->has('type_id')) {
            $deposit->type_id = $request->type_id !== 'Choose type...' ? $request->type_id : null;
        }
        if ($request->has('unit_id')) {
            $deposit->unit_id = $request->unit_id !== 'Choose unit...' ? $request->unit_id : null;
        }

        $deposit->save();

        $msg = 'Success: Deposit has been successfully updated';

        if (request()->query('route')) {
            return redirect()->route(request()->query('route'))->with('success', $msg);
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposit $deposit)
    {
        $deposit->delete();

        $route = request()->query('route') ?: 'deposits.index';

        return redirect()->route($route)->with(['success' => 'Success: '.__('Deposit amount').' '.$deposit->amount.' has been deleted successfuly']);
    }
}
