<div class="rpd-dataform">
        @section('df.header')
                {!! $df->open !!}
        @show

        @if ($df->message != '')
        @section('df.message')
                <div class="alert alert-success">{!! $df->message !!}</div>
        @show
        @endif

        @if ($df->message == '')
        @section('df.fields')

                @each('rapids::form.field', $df->fields, 'field')

        @show
        @endif
            {!! $df->submit !!}
            {!! $df->reset !!}
        @section('df.footer')
                {!! $df->close !!}
        @show
</div>