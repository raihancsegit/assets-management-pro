<?php

namespace App\Http\Controllers\Types;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTypeRequest;
use App\Http\Requests\UpdateTypeRequest;
use App\Models\Category;
use App\Models\Scheme;
use App\Models\Type;

class TypeController extends Controller
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
        $types = Type::with(['scheme'])
            ->latest('id')
            ->paginate(10);

        $categories = Category::all();
        $schemes = Scheme::all();

        return view('admin.types.index', compact(
            'types',
            'categories',
            'schemes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeRequest $request)
    {
        (new Type($request->toArray()))->save();

        return redirect()->route('types.index')->with('success', 'Success: '.$request->name.' added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Type $type)
    {
        $categories = Category::all();
        $schemes = Scheme::all();

        return view('admin.types.edit', compact('type', 'categories', 'schemes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeRequest $request, Type $type)
    {
        ($type->fill($request->toArray()))->save();

        return back()->with('success', 'Success: '.$request->name.' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $type->delete();

        return redirect()->route('types.index')->with(['success' => 'Success: '.$type->name.' has been deleted successfuly']);
    }
}
