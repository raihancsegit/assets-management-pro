@extends('layouts/admin')
@section('meta-title', 'All Users - Assets Management Pro')
@section('page-title', 'All Users')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('Users represent your assets item.') }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('Users') }}</h4>
                </div>
                <div class="card-body">
                    @if($users->count())
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <th scope="row">{{++$key}}</th>
                                            <td><a href="{{ route('users.show', $user->id) }}">{{$user->name}}</a></td>
                                            <td>{{$user->email}}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                    <div class="btn-group" role="group">
                                                        <button id="btnGroupDrop1" type="button" class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-h"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                            <li><a class="dropdown-item" href="{{route('users.show', $user->id)}}">Edit</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-container mt-4 mb-0">
                                {{ $users->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    @else
                        <p class="text-muted font-size-13 mb-0"><i>{{ __('No data') }}</i></p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Add New User') }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="user_name">{{ __('Name') }}</label>
                                <input class="form-control form-control-lg" type="text" name="name" value="{{old('name')}}" id="user_name" placeholder="{{ __('admin') }}" required>
                                @error('name')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="email">{{ __('Email') }}</label>
                                <input class="form-control form-control-lg" type="email" name="email" value="{{old('email')}}" id="email" placeholder="example@example.local" required>
                                @error('email')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="password">{{ __('Password') }}</label>
                                <input class="form-control form-control-lg" type="password" name="password" value="{{old('password')}}" id="password" required>
                                @error('password')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                                <input class="form-control form-control-lg" type="password" name="password_confirmation" value="{{old('password_confirmation')}}" id="password_confirmation" required>
                                @error('password_confirmation')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="role">{{ __('Role') }}</label>
                                <select name="role" class="form-select">
                                    @if (auth()->user()->hasRole('staff'))
                                        <option value="staff">Staff</option>
                                        <option value="admin">Admin</option>
                                    @endif
                                    <option value="manager" selected>Manager</option>
                                </select>
                                @error('role')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('Add User') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection


