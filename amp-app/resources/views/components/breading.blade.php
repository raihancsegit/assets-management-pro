@props(['data', 'viewRoute', 'route', 'categoryId'])

@if($data->count())
    <div class="table-responsive category-table">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Parent') }}</th>
                    <th>{{ __('Serial') }}</th>
                    <th>{{ __('Color') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Value Amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $inventorie)
                    @php
                        $getViewRoute = $categoryId
                            ? route($viewRoute, [$categoryId, $inventorie->id, 'route' => $route])
                            : route($viewRoute, [$inventorie->id, 'route' => $route]);

                        if($inventorie->status == 1 && isManager()){
                            $getViewRoute = '#';
                        }
                    @endphp
                    <tr>
                        <th scope="row">{{++$key}}</th>
                        <td>
                            {{$inventorie->created_at->format('d M, Y')}}
                        </td>
                        <td>
                            <a href="{{$getViewRoute}}">{{ $inventorie->name }}</a>
                        </td>
                        <td>
                            {{ $inventorie->parent->name }}
                        </td>

                        <td>
                            {{$inventorie->serial}}
                        </td>

                        <td>
                            {{$inventorie->color}}
                        </td>

                        <td>
                            {{ $inventorie->inventory_type ? $inventorie->inventory_type->name : '' }}
                        </td>

                        <td>
                            {{$inventorie->value_amount}}
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
