<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Category;
use App\Models\Inventorie_type;
use App\Models\Inventory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class CategoryInventorysController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category, Request $request)
    {
        $queryBuilder = $category->inventories->whereNull('parent_id');

        $inventories = $this->paginate($queryBuilder, 20, null, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
            'orderBy' => ['id' => 'desc'],
        ])->withQueryString();

        $route = 'categories.inventories.index';

        return view('admin.categories.inventories.index', compact('inventories', 'category', 'route'));
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
     * Create resource
     */
    public function create(Category $category)
    {
        $route = request()->query('route');
        $types = Inventorie_type::all();
        $parent_inventories = Inventory::whereNull('parent_id')->get();

        return view('admin.categories.inventories.create', compact(
            'category',
            'route',
            'types',
            'parent_inventories'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryRequest $request, Category $category, Inventory $inventory)
    {
        (new Inventory($request->toArray()))->save();
        $msg = __('messages.Success: Inventory has been added successfully');
        $msg2 = __('messages.Success: Breading has been added successfully');
        if ($request->has('category_page')) {
            return back()->with('success', $msg);
        }
        if ($request->input('parent_id') === null) {
            // Redirect to the same page
            return redirect()->route('categories.inventories.index', $category->id)->with('success', $msg);

        } else {
            // Redirect to other page
            return redirect()->route('categories.breadings.index', $category->id)->with('success', $msg2);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Inventory $inventory)
    {
        $categories = Category::get(['id', 'name']);
        $route = request()->query('route');
        $types = Inventorie_type::all();

        $parent_inventories = Inventory::whereNull('parent_id')->get();

        return view('admin.categories.inventories.edit', compact(
            'inventory',
            'category',
            'categories',
            'route',
            'types',
            'parent_inventories'
        ));
    }

    public function edit(Category $category, Inventory $inventory)
    {
        $categories = Category::get(['id', 'name']);
        $route = request()->query('route');
        $types = Inventorie_type::all();

        $parent_inventories = Inventory::whereNull('parent_id')->get();

        return view('admin.categories.inventories.edit', compact(
            'inventory',
            'category',
            'categories',
            'route',
            'types',
            'parent_inventories'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryRequest $request, Category $category, Inventory $inventory)
    {
        ($inventory->fill($request->toArray()))->save();
        $msg = __('messages.Success: Inventory has been successfully updated');

        if (request()->query('route')) {
            return redirect()->route(request()->query('route'), $category->id)->with('success', $msg);
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
