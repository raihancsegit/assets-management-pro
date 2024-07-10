<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * construct method.
     */
    public function __construct()
    {
        $this->middleware(['role:staff|admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve categories with eager loading of sums for deposits, expanses, and incomes
        $categories = Category::withSum(['deposits' => function ($query) {
            $query->where('status', 1);
        }], 'amount')
            ->withSum(['expanses' => function ($query) {
                $query->where('status', 1);
            }], 'amount')
            ->withSum(['incomes' => function ($query) {
                $query->where('status', 1);
            }], 'amount')
            ->get();

        // Calculate totals by summing up the preloaded sums
        $total_deposits = $categories->sum('deposits_sum_amount');
        $total_expanses = $categories->sum('expanses_sum_amount');
        $total_incomes = $categories->sum('incomes_sum_amount');

        // Retrieve parent categories
        $parent_categories = Category::whereNull('parent_id')
            ->with([
                'children',
                'deposits' => function ($query) {
                    $query->where('status', 1);
                },
                'expanses' => function ($query) {
                    $query->where('status', 1);
                },
                'incomes' => function ($query) {
                    $query->where('status', 1);
                },
            ])
            ->latest('id')
            ->paginate(10);

        return view('admin.categories.index', compact(
            'total_deposits',
            'total_expanses',
            'total_incomes',
            'parent_categories'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return $this->index();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        (new Category($request->toArray()))->save();

        return redirect()->route('categories.index')->with('success', 'Success: '.$request->name.' added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $subcategory = Category::findOrFail($category->id);
        $parent_categories = Category::whereNull('parent_id')->get();

        return view('admin.categories.edit', compact('category', 'parent_categories', 'subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $subcategory = Category::findOrFail($category->id);
        $parent_categories = Category::whereNull('parent_id')->get();

        return view('admin.categories.edit', compact('category', 'parent_categories', 'subcategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        ($category->fill($request->toArray()))->save();

        return back()->with('success', 'Success: '.$request->name.' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if (
            $category->deposits->count()
            || $category->expanses->count()
            || $category->incomes->count()
        ) {
            return back()->withErrors(['msg' => 'Failed: '.$category->name.' has relation with deposits/expanses/incomes']);
        }

        $category->delete();

        return redirect()->route('categories.index')->with(['success' => 'Success: '.$category->name.' has been deleted successfuly']);
    }
}
