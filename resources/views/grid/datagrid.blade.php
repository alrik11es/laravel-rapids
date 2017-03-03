{!! $filter !!}

@if($link)
    {!! $link !!}
@endif
<div class="clearfix"></div>
<div class="table-responsive">
    <table class="table">
        <tr>
            @foreach($fields as $field)
                <th>

                    @if($field->needs_order)
                        <span class="ordering">
                            @if(Request::input('ord_'.$field->field_id) != 'asc')
                            <a href="{{ $ord['ord_asc_'.$field->field_id] }}"><i class="glyphicon glyphicon-chevron-up"></i></a>
                            <i class="glyphicon glyphicon-chevron-down"></i>
                            @else
                            <i class="glyphicon glyphicon-chevron-up"></i>
                            <a href="{{ $ord['ord_desc_'.$field->field_id] }}"><i class="glyphicon glyphicon-chevron-down"></i></a>
                            @endif
                        </span>
                    @endif

                    {{ $field->name }}
                </th>
            @endforeach
            @if($actions)
                <th>@lang('rapids::rapids.actions')</th>
            @endif
        </tr>
        @forelse($paginator as $row)
            <tr>
                {{--@if($hasBatch && $__batch_id = $row->getKey())--}}
                    {{--<td>--}}
                        {{--<input type="checkbox" class="lego-batch-checkbox" data-batch-id="{{ $__batch_id }}">--}}
                    {{--</td>--}}
                {{--@endif--}}
                @foreach($fields as $cell)
                    <td>{!! $row[$cell->field_id] !!}</td>
                @endforeach


                @if($actions)
                    <td>
                        @if (in_array("show", $actions))
                            <a class="" title="@lang('rapids::rapids.show')" href="{!! $url !!}/{!! $row['id'] !!}">
                                <span class="glyphicon glyphicon-eye-open"> </span>
                            </a>
                        @endif
                        @if (in_array("modify", $actions))
                            <a class="" title="@lang('rapids::rapids.modify')" href="{!! $url !!}/{!! $row['id'] !!}/edit">
                                <span class="glyphicon glyphicon-edit"> </span>
                            </a>
                        @endif
                        @if (in_array("delete", $actions))
                            {{ resolve('form')->open(['url' => $url.'/'.$row['id'], 'method' => 'delete']) }}
                            <button type="submit" class="text-danger" title="@lang('rapids::rapids.delete')">
                                <span class="glyphicon glyphicon-trash"> </span>
                            </button>
                            {{ resolve('form')->close() }}
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($fields) + ($actions ? 1 : 0) }}">
                    No hay resultados
                </td>
            </tr>
        @endforelse
    </table>
</div>

<div class="text-center">
    {!! $paginator->links() !!}
</div>

{{--@include('lego::default.snippets.bottom-buttons', ['widget' => $grid])--}}