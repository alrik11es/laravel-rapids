@if($link)
    {!! $link !!}
@endif
{!! $filter !!}
<div class="clearfix"></div>
<p class="table-responsive">
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
                    <td style="width: 23%">
                        <form action="{{ $url.'/'.$row['id'] }}" method="post" class="form-horizontal">
                            <input name="_method" type="hidden" value="DELETE">
                            {{ csrf_field() }}
                        @if (in_array("show", $actions))
                            <a class="btn btn-primary" title="@lang('rapids::rapids.show')" href="{!! $url !!}/{!! $row['id'] !!}">
                                <span class="glyphicon glyphicon-eye-open"> </span>
                            </a>
                        @endif
                        @if (in_array("modify", $actions))
                            <a class="btn btn-default" title="@lang('rapids::rapids.modify')" href="{!! $url !!}/{!! $row['id'] !!}/edit">
                                <span class="glyphicon glyphicon-edit"> </span>
                            </a>
                        @endif
                        @if (in_array("delete", $actions))


                            <button type="submit" class="btn btn-danger" title="@lang('rapids::rapids.delete')">
                                <span class="glyphicon glyphicon-trash"> </span>
                            </button>
                        @endif
                        </form>
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
</p>

<div class="text-center">
    {!! $paginator->links() !!}
</div>

{{--@include('lego::default.snippets.bottom-buttons', ['widget' => $grid])--}}