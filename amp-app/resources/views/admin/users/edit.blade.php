@extends('layouts/admin')
@section('meta-title', 'Update User - Assets Management Pro')
@section('page-title', 'Update User')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <p>{{ __('User') }} - {{ $user->name }}</p>
        </div>
        <x-alert />
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Update User') }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('put')
                            <input type="hidden" name="_id" value="{{ $user->id }}">
                            <div class="mb-4">
                                <label class="form-label" for="cat_name">{{ __('User Name') }}</label>
                                <input class="form-control form-control-lg" type="text" name="name" value="{{old('name', $user->name)}}" id="cat_name" placeholder="{{ __('name') }}" required>
                                @error('name')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="email">{{ __('Email') }}</label>
                                <input class="form-control form-control-lg" type="text" name="email" value="{{old('email', $user->email)}}" id="email" placeholder="{{ __('Email') }}">
                                @error('email')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>
                            @if(auth()->user()->hasRole('staff'))
                                <div class="mt-4">
                                    <label class="form-label" for="role">{{ __('Role') }}</label>
                                    <select name="role" class="form-select">
                                        <option value="staff" @php echo $user->hasRole('staff') ? 'selected' : '' @endphp>Staff</option>
                                        <option value="admin" @php echo $user->hasRole('admin') ? 'selected' : '' @endphp>Admin</option>
                                        <option value="manager" @php echo $user->hasRole('manager') ? 'selected' : ''  @endphp>Manager</option>
                                    </select>
                                    @error('role')
                                        <code>{{ $message }}</code>
                                    @enderror
                                </div>
                            @else
                                <input type="hidden" name="role" value="{{$user->getRoleNames()->toArray()[0]}}" />
                            @endif

                            <div class="mt-4">
                                <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('Update Password') }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update-password', $user->id) }}">
                            @csrf
                            @method('put')
                            <input type="hidden" name="_id" value="{{ $user->id }}">
                            <div>
                                <label class="form-label" for="password">{{ __('Password') }}</label>
                                <input class="form-control form-control-lg" type="password" name="password"  id="password" placeholder="{{ __('Password') }}">
                                @error('password')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                                <input class="form-control form-control-lg" type="password" name="password_confirmation"  id="password_confirmation" placeholder="{{ __('Confirm Password') }}">
                                @error('password_confirmation')
                                    <code>{{ $message }}</code>
                                @enderror
                            </div>


                            <div class="mt-4">
                                <button type="submit" class="btn btn-subtle-primary waves-effect waves-light">{{ __('Update Password') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>

        <div class="col-lg-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('User Details') }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="lead">{{ $user->name }}</p>
                        <p class="text-muted font-size-13 mt-4 mb-0"><i>{{ __('Last updated ') }}{{ $user->updated_at->diffForHumans() }}</i></p>
                    </div>
                </div>
            </div>

            @if($user->id !== auth()->user()->id)
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ __('Delete User') }}</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf

                                @method('DELETE')

                                <button type="submit" class="btn btn-danger waves-effect waves-light" onclick="return confirm('Are you sure?');">
                                    <i class="bx bx-block font-size-16 align-middle me-2"></i> {{ __('Delete') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection


