<?php

namespace App\Http\Controllers\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class CategoryBreadingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category, Request $request)
    {
        $queryBuilder = $category->breadings;

        $inventories = $this->paginate($queryBuilder, 10, null, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
            'orderBy' => ['id' => 'desc'],
        ])->withQueryString();

        $route = 'categories.inventories.index';

        return view('admin.categories.breadings.index', compact('inventories', 'category', 'route'));
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
}
