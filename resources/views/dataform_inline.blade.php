<div>
    {{--@section('df.header')--}}
        {!! $df->open !!}
        {{--@include('rapids::toolbar', array('label'=>$df->label, 'buttons_right'=>$df->button_container['TR']))--}}
    {{--@show--}}

    @if ($df->message != '')
{{--    @section('df.message')--}}
        <div class="alert alert-success">{!! $df->message !!}</div>
    {{--@show--}}
    @endif

    @if ($df->message == '')
{{--    @section('df.fields')--}}

        @each('rapids::form.field_inline', $df->fields, 'field')

    {{--@show--}}
    @endif

        {!! $df->submit !!}
        {!! $df->reset !!}
{{--    @section('df.footer')--}}

        {{--@if (isset($df->button_container['BL']) && count($df->button_container['BL']))--}}

            {{--@foreach ($df->button_container['BL'] as $button) {!! $button !!}--}}
            {{--@endforeach--}}

        {{--@endif--}}

        {!! $df->close !!}
    {{--@show--}}
</div>