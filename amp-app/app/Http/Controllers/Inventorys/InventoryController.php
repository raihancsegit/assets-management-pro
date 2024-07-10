<?php

namespace App\Http\Controllers\Inventorys;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Category;
use App\Models\Inventorie_type;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::with([
            'category',
        ])->latest('id')->paginate(10);

        $route = 'inventories.index';

        return view('admin.inventories.index', compact('inventories', 'route'));
    }

    /**
     * Pagination
     */
    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('inventories.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryRequest $request)
    {
        $attr = [
            'status' => 1,
            'created_by' => Auth()->user()->id,
        ];
        $attr = array_merge($attr, $request->toArray());

        (new Inventory($attr))->save();

        $msg = __('messages.Success: Inventory has been added successfully');

        if ($request->has('category_page')) {
            return redirect()->route('categories.inventories.index', $request->category_id)->with('success', $msg);
        }

        return redirect()->route('inventories.index')->with('success', $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Inventory $inventory)
    {
        $categories = Category::get(['id', 'name']);
        $route = request()->query('route');
        $types = Inventorie_type::all();

        $queryBuilder = $category->inventories;

        $parent_inventories = $this->paginate($queryBuilder, 10, null, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
            'orderBy' => ['id' => 'desc'],
        ])->withQueryString();

        return view('admin.inventories.edit', compact(
            'inventory',
            'categories',
            'route',
            'types',
            'parent_inventories'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category, Inventory $inventory)
    {
        $categories = Category::get(['id', 'name']);
        $types = Inventorie_type::all();
        $route = request()->query('route');

        $queryBuilder = $category->inventories;

        $parent_inventories = $this->paginate($queryBuilder, 10, null, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
            'orderBy' => ['id' => 'desc'],
        ])->withQueryString();

        return view('admin.inventories.edit', compact('inventory', 'categories', 'route', 'types', 'parent_inventories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryRequest $request, Inventory $inventory)
    {

        ($inventory->fill($request->toArray()))->save();
        $msg = __('messages.Success: Inventory has been successfully updated');

        if (request()->query('route')) {
            return redirect()->route(request()->query('route'))->with('success', $msg);
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Inventory $inventory)
    {
        $inventory->delete();

        $route = request()->query('route') ?: 'inventories.index';

        return redirect()->route($route, [$category->id])->with(['success' => 'Success: '.$inventory->name.' Inventory has been deleted successfuly']);
    }
}
