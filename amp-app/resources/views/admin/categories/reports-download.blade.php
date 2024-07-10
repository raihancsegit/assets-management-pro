<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <br/>
                <div><i>Download at {{ date('Y-m-d h:i:s') }}</i></div>
                <h4 class="card-title">{{ __('Date wise report for ' . ' #' . $category->id . ' ' . $category->name) }}</h4>
            </div>
            <div class="card-body">
                @if($data->count())
                    <div class="table-responsive category-table">
                        <table width="500" border="1">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Deposits') }}</th>
                                    <th>{{ __('Expanses') }}</th>
                                    <th>{{ __('Incomes') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                    @php
                                        $balance = ($row->deposits - $row->expanses) + $row->incomes;
                                    @endphp
                                    <tr>
                                        <td>{{ dateFormat($row->date, 'd M, Y') }}</td>
                                        <td>{{ $row->deposits }}</td>
                                        <td>{{ $row->expanses }}</td>
                                        <td>{{ $row->incomes }}</td>
                                        <td>{{ $balance }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted font-size-13 mb-0"><i>{{ __('messages.No data') }}</i></p>
                @endif
            </div>
        </div>
    </div>
</div>
