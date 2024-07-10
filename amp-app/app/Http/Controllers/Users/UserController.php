<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Throwable;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:staff|admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest('id')->paginate(10);

        return view('admin.users.index', compact(
            'users'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = new User($request->toArray());
            $user->save();

            // Assign Role
            $role = Role::findOrCreate(auth()->user()->hasRole('staff') ? $request->role : 'manager');
            $user->assignRole($role);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('users.index')->with('success', 'Success: '.$request->name.' added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            ($user->fill($request->toArray()))->save();

            // Sync Role
            $user->syncRoles($request->role);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Failed: '.$e->getMessage());
        }

        return back()->with('success', 'Success: '.$request->name.' updated successfully');

    }

    /**
     * Update the password field.
     */
    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Success: '.$request->name.' password updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with(['success' => 'Success: '.$user->name.' has been deleted successfuly']);
    }
}
