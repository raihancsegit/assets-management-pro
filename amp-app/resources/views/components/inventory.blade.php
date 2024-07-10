@props(['data', 'viewRoute', 'route', 'categoryId'])

@if($data->count())
    <div class="table-responsive">
        <table class="table table-borderless mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Serial') }}</th>
                    <th>{{ __('Color') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Value Amount') }}</th>
                    <th>{{ __('Shade No') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $d)
                    @php
                        $getViewRoute = $categoryId
                            ? route($viewRoute, [$categoryId, $d->id, 'route' => $route])
                            : route($viewRoute, [$d->id, 'route' => $route]);

                        if($d->status == 1 && isManager()){
                            $getViewRoute = '#';
                        }
                    @endphp
                    <tr>
                        <th scope="row">{{++$key}}</th>
                        <td>
                            <a href="{{ $getViewRoute }}">
                                {{$d->created_at->format('d M, Y')}}
                            </a>
                        </td>
                        <td>
                            {{ $d->name }}
                        </td>
                        <td>
                            {{ $d->serial }}
                        </td>
                        <td>{{ $d->color}}</td>

                        <td>
                            {{ $d->inventory_type ? $d->inventory_type->name : '' }}
                        </td>

                        <td>
                            {{ $d->value_amount }}
                        </td>

                        <td>
                            {{ $d->shade_no }}
                        </td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>
                                    @if(auth()->user()->hasRole('staff|admin'))
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="{{ $getViewRoute }}">{{__('messages.Edit')}}</a></li>
                                        </ul>
                                    @elseif($d->status == 0)
                                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                            <li><a class="dropdown-item" href="{{ $getViewRoute }}">{{__('messages.Edit')}}</a></li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(method_exists($data, 'links'))
            <div class="pagination-container mt-4 mb-0">
                {{ $data->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
@else
    <p class="text-muted font-size-13 mb-0"><i>{{ __('No data') }}</i></p>
@endif
