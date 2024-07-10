<?php

namespace App\Http\Controllers\Categories;

use App\Filters\SchemeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\Category;
use App\Models\Income;
use App\Models\Scheme;
use App\Models\Type;
use App\Models\Unit;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CategoryIncomesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category, Request $request)
    {
        $queryBuilder = $category->incomes;

        $queryBuilder = resolve(SchemeFilter::class)->getResults([
            'model' => $queryBuilder,
            'params' => $request->query(),
        ]);

        $incomes = $this->paginate($queryBuilder, 10, null, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
            'orderBy' => ['id' => 'desc'],
        ])->withQueryString();

        $route = 'categories.incomes.index';

        return view('admin.categories.incomes.index', compact('incomes', 'category', 'route'));
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
        $types = Type::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', 'income')->first()->id)
            ->get(['id', 'name']);

        $units = Unit::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', 'income')->first()->id)
            ->get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.categories.incomes.create', compact(
            'category',
            'types',
            'units',
            'route'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIncomeRequest $request, Category $category, Income $income)
    {
        $this->upsert($income, $request);

        $msg = 'Success: Income has been added successfully';
        if ($request->has('category_page')) {
            return back()->with('success', $msg);
        }

        return redirect()->route('categories.incomes.index', $category->id)->with('success', $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Income $income)
    {
        if ($income->status == 1 && isManager()) {
            return back();
        }
        $categories = Category::get(['id', 'name']);
        $types = Type::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[2])->first()->id)
            ->get(['id', 'name']);

        $units = Unit::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[2])->first()->id)
            ->get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.categories.incomes.edit', compact(
            'income',
            'category',
            'categories',
            'types',
            'units',
            'route'
        ));
    }

    public function edit(Category $category, Income $income)
    {
        if ($income->status == 1 && isManager()) {
            return back();
        }
        $categories = Category::get(['id', 'name']);
        $types = Type::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[2])->first()->id)
            ->get(['id', 'name']);

        $units = Unit::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[2])->first()->id)
            ->get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.categories.incomes.edit', compact(
            'income',
            'category',
            'categories',
            'types',
            'units',
            'route'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIncomeRequest $request, Category $category, Income $income)
    {
        $this->upsert($income, $request, true);

        $msg = 'Success: Income has been successfully updated';

        if (request()->query('route')) {
            return redirect()->route(request()->query('route'), $category->id)->with('success', $msg);
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Income $income)
    {
        $income->delete();

        if (isset($income->attachment->id)) {
            $this->deleteAttachmentStorage('attachments/'.$income->attachment->url);
            $income->attachment->delete();
        }

        $route = request()->query('route') ?: 'incomes.index';

        return redirect()->route($route, [$category->id])->with(['success' => 'Success: '.__('Income amount').' '.$income->amount.' has been deleted successfuly']);
    }

    /**
     * Upsert
     */
    public function upsert(Income $income, $request, $isUpdate = false)
    {
        try {
            DB::beginTransaction();

            $income->date = $request->has('date') ? new Datetime($request->date) : new DateTime();
            $income->amount = $request->amount;
            $income->receipt_no = $request->receipt_no;
            $income->unit_value = $request->unit_value;
            $income->details = $request->details;
            $income->notes = $request->notes;
            $income->category_id = $request->category_id;
            $income->status = isManager() ? 0 : $request->status;

            if ($isUpdate) {
                $income->updated_by = Auth()->user()->id;
            } else {
                $income->created_by = Auth()->user()->id;
            }

            if ($request->has('type_id')) {
                $income->type_id = $request->type_id !== 'Choose type...' ? $request->type_id : null;
            }
            if ($request->has('unit_id')) {
                $income->unit_id = $request->unit_id !== 'Choose unit...' ? $request->unit_id : null;
            }

            $income->save();

            $file = $request->file('attachment');
            if ($file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $file->storeAs('attachments', $fileName);
                $data = ['url' => $fileName, 'attachmentable_id' => $income->id];
                if (isset($income->attachment->id)) {
                    $income->attachment()->update($data);
                } else {
                    $income->attachment()->create($data);
                }

                if (isset($income->attachment->id)) {
                    $this->deleteAttachmentStorage('attachments/'.$income->attachment->url);
                }
            }

            DB::commit();

            return $income;
        } catch (Throwable $e) {
            DB::rollBack();

            return $e;
        }
    }

    /**
     * Delete attachment
     */
    public function deleteAttachmentStorage($file)
    {
        if (Storage::exists($file)) {
            return Storage::delete($file);
        }

        return false;
    }
}
