<?php

namespace App\Http\Controllers\Categories;

use App\Filters\SchemeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpanseRequest;
use App\Http\Requests\UpdateExpanseRequest;
use App\Models\Category;
use App\Models\Expanse;
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

class CategoryExpansesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category, Request $request)
    {
        $queryBuilder = $category->expanses;

        $queryBuilder = resolve(SchemeFilter::class)->getResults([
            'model' => $queryBuilder,
            'params' => $request->query(),
        ]);

        $expanses = $this->paginate($queryBuilder, 10, null, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
            'orderBy' => ['id' => 'desc'],
        ])->withQueryString();

        $route = 'categories.expanses.index';

        return view('admin.categories.expanses.index', compact('expanses', 'category', 'route'));
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
            ->where('scheme_id', Scheme::where('name', 'expanse')->first()->id)
            ->get(['id', 'name']);

        $units = Unit::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', 'expanse')->first()->id)
            ->get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.categories.expanses.create', compact(
            'category',
            'types',
            'units',
            'route'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExpanseRequest $request, Category $category, Expanse $expanse)
    {

        $this->upsert($expanse, $request);
        $msg = 'Success: Expanses has been added successfully';
        if ($request->has('category_page')) {
            return back()->with('success', $msg);
        }

        return redirect()->route('categories.expanses.index', $category->id)->with('success', $msg);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Expanse $expanse)
    {
        if ($expanse->status == 1 && isManager()) {
            return back();
        }
        $categories = Category::get(['id', 'name']);
        $types = Type::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[1])->first()->id)
            ->get(['id', 'name']);

        $units = Unit::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[1])->first()->id)
            ->get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.categories.expanses.edit', compact(
            'expanse',
            'category',
            'categories',
            'types',
            'units',
            'route'
        ));
    }

    public function edit(Category $category, Expanse $expanse)
    {
        if ($expanse->status == 1 && isManager()) {
            return back();
        }
        $categories = Category::get(['id', 'name']);
        $types = Type::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[1])->first()->id)
            ->get(['id', 'name']);

        $units = Unit::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[1])->first()->id)
            ->get(['id', 'name']);

        $route = request()->query('route');

        return view('admin.categories.expanses.edit', compact(
            'expanse',
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
    public function update(UpdateExpanseRequest $request, Category $category, Expanse $expanse)
    {

        $this->upsert($expanse, $request, true);

        $msg = 'Success: Expanse has been successfully updated';

        if (request()->query('route')) {
            return redirect()->route(request()->query('route'), $category->id)->with('success', $msg);
        }

        return back()->with('success', $msg);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Expanse $expanse)
    {
        $expanse->delete();

        if (isset($expanse->attachment->id)) {
            $this->deleteAttachmentStorage('attachments/'.$expanse->attachment->url);
            $expanse->attachment->delete();
        }

        $route = request()->query('route') ?: 'expanses.index';

        return redirect()->route($route, [$category->id])->with(['success' => 'Success: '.__('Expanse amount').' '.$expanse->amount.' has been deleted successfuly']);
    }

    /**
     * Upsert
     */
    public function upsert(Expanse $expanse, $request, $isUpdate = false)
    {
        try {
            DB::beginTransaction();

            $expanse->date = $request->has('date') ? new Datetime($request->date) : new DateTime();
            $expanse->amount = $request->amount;
            $expanse->receipt_no = $request->receipt_no;
            $expanse->unit_value = $request->unit_value;
            $expanse->details = $request->details;
            $expanse->notes = $request->notes;
            $expanse->category_id = $request->category_id;
            $expanse->status = isManager() ? 0 : $request->status;

            if ($isUpdate) {
                $expanse->updated_by = Auth()->user()->id;
            } else {
                $expanse->created_by = Auth()->user()->id;
            }

            if ($request->has('type_id')) {
                $expanse->type_id = $request->type_id !== 'Choose type...' ? $request->type_id : null;
            }
            if ($request->has('unit_id')) {
                $expanse->unit_id = $request->unit_id !== 'Choose unit...' ? $request->unit_id : null;
            }

            $expanse->save();

            $file = $request->file('attachment');
            if ($file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $file->storeAs('attachments', $fileName);
                $data = ['url' => $fileName, 'attachmentable_id' => $expanse->id];
                if (isset($expanse->attachment->id)) {
                    $expanse->attachment()->update($data);
                } else {
                    $expanse->attachment()->create($data);
                }

                if (isset($expanse->attachment->id)) {
                    $this->deleteAttachmentStorage('attachments/'.$expanse->attachment->url);
                }
            }

            DB::commit();

            return $expanse;
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
