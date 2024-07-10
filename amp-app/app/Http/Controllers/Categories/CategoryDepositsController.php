<?php

namespace App\Http\Controllers\Categories;

use App\Filters\SchemeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\UpdateDepositRequest;
use App\Models\Category;
use App\Models\Deposit;
use App\Models\Scheme;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CategoryDepositsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:staff|admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Category $category, Request $request)
    {
        $queryBuilder = $category->deposits;

        $queryBuilder = resolve(SchemeFilter::class)->getResults([
            'model' => $queryBuilder,
            'params' => $request->query(),
        ]);

        $deposits = $this->paginate($queryBuilder, 10, null, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
            'orderBy' => ['id' => 'desc'],
        ])->withQueryString();

        $route = 'categories.deposits.index';

        return view('admin.categories.deposits.index', compact('deposits', 'category', 'route'));
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
     * Store a newly created resource in storage.
     */
    public function store(StoreDepositRequest $request, Category $category, Deposit $deposit)
    {
        $this->upsert($deposit, $request);

        $msg = 'Success: Deposit has been added successfully';
        if ($request->has('category_page')) {
            return back()->with('success', $msg);
        }

        return redirect()->route('categories.deposits.index', $category->id)->with('success', $msg);
    }

    /**
     * Create resource
     */
    public function create(Category $category)
    {
        $types = Type::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', 'deposit')->first()->id)
            ->get(['id', 'name']);

        $units = Unit::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', 'deposit')->first()->id)
            ->get(['id', 'name']);

        $managers = User::role('manager')->get(); // Load users with the manager role

        $route = request()->query('route');

        return view('admin.categories.deposits.create', compact(
            'category',
            'types',
            'units',
            'managers', // Pass managers to the view
            'route'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Deposit $deposit)
    {
        if ($deposit->status == 1 && isManager()) {
            return back();
        }
        $categories = Category::get(['id', 'name']);

        $types = Type::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[0])->first()->id)
            ->get(['id', 'name']);

        $units = Unit::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[0])->first()->id)
            ->get(['id', 'name']);

        $managers = User::role('manager')->get(); // Load users with the manager role

        $depositId = DB::table('deposits')
            ->leftJoin('deposit_managers', 'deposits.id', '=', 'deposit_managers.deposit_id')
            ->select('deposits.*', 'deposit_managers.manager_id')
            ->where('deposits.id', $deposit->id)
            ->first();

        $route = request()->query('route');

        return view('admin.categories.deposits.edit', compact(
            'deposit',
            'category',
            'categories',
            'types',
            'units',
            'managers', // Pass managers to the view
            'route',
            'depositId',
        ));
    }

    public function edit(Category $category, Deposit $deposit)
    {
        if ($deposit->status == 1 && isManager()) {
            return back();
        }
        $categories = Category::get(['id', 'name']);

        $types = Type::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[0])->first()->id)
            ->get(['id', 'name']);

        $units = Unit::where('category_id', $category->id)
            ->where('scheme_id', Scheme::where('name', (getSchemeName())[0])->first()->id)
            ->get(['id', 'name']);

        $managers = User::role('manager')->get(); // Load users with the manager role

        $depositId = DB::table('deposits')
            ->leftJoin('deposit_managers', 'deposits.id', '=', 'deposit_managers.deposit_id')
            ->select('deposits.*', 'deposit_managers.manager_id')
            ->where('deposits.id', $deposit->id)
            ->first();

        $route = request()->query('route');

        return view('admin.categories.deposits.edit', compact(
            'deposit',
            'category',
            'categories',
            'types',
            'units',
            'managers', // Pass managers to the view
            'route',
            'depositId',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepositRequest $request, Category $category, Deposit $deposit)
    {
        $this->upsert($deposit, $request, true);

        $msg = 'Success: Deposit has been successfully updated';

        if (request()->query('route')) {
            return redirect()->route(request()->query('route'), $category->id)->with('success', $msg);
        }

        return back()->with('success', $msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Deposit $deposit)
    {
        $deposit->delete();

        if (isset($deposit->attachment->id)) {
            $this->deleteAttachmentStorage('attachments/'.$deposit->attachment->url);
            $deposit->attachment->delete();
        }

        $route = request()->query('route') ?: 'deposits.index';

        return redirect()->route($route, [$category->id])->with(['success' => 'Success: '.__('Deposit amount').' '.$deposit->amount.' has been deleted successfully']);
    }

    /**
     * Upsert
     */
    public function upsert(Deposit $deposit, $request, $isUpdate = false)
    {
        try {
            DB::beginTransaction();

            $deposit->date = $request->has('date') ? new Datetime($request->date) : new DateTime();
            $deposit->amount = $request->amount;
            $deposit->receipt_no = $request->receipt_no;
            $deposit->unit_value = $request->unit_value;
            $deposit->details = $request->details;
            $deposit->notes = $request->notes;
            $deposit->category_id = $request->category_id;
            $deposit->status = isManager() ? 0 : $request->status;

            if ($isUpdate) {
                $deposit->updated_by = Auth()->user()->id;
            } else {
                $deposit->created_by = Auth()->user()->id;
            }

            if ($request->has('type_id')) {
                $deposit->type_id = $request->type_id !== 'Choose type...' ? $request->type_id : null;
            }
            if ($request->has('unit_id')) {
                $deposit->unit_id = $request->unit_id !== 'Choose unit...' ? $request->unit_id : null;
            }

            $deposit->save();

            $manager = User::find($request->input('manager_id'));

            // Save manager information if in_hand is checked
            if ($request->has('in_hand') && $request->in_hand) {
                $deposit->in_hand = true;
                $manager_id = $request->input('manager_id');
                if ($manager_id) {
                    DB::table('deposit_managers')->updateOrInsert(
                        ['deposit_id' => $deposit->id],
                        ['manager_id' => $manager_id]
                    );
                }
            } else {
                // If in_hand is not checked, remove any associated manager
                DB::table('deposit_managers')->where('deposit_id', $deposit->id)->delete();
                //$deposit->manager()->detach($manager->id);
            }

            $file = $request->file('attachment');
            if ($file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $file->storeAs('attachments', $fileName);
                $data = ['url' => $fileName, 'attachmentable_id' => $deposit->id];
                if (isset($deposit->attachment->id)) {
                    $deposit->attachment()->update($data);
                } else {
                    $deposit->attachment()->create($data);
                }

                if (isset($deposit->attachment->id)) {
                    $this->deleteAttachmentStorage('attachments/'.$deposit->attachment->url);
                }
            }

            DB::commit();

            return $deposit;
        } catch (Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
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