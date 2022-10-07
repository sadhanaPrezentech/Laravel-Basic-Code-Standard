{!! Form::open(['id' => $form_id]) !!}
<div class="row ">

    <div class="col-sm">
        <div class="form-group mb-4">
            {!! Form::text('search[value]', null, ['class' => 'form-control', 'placeholder' =>  'Enter Keyword',"autocomplete" => 'off']) !!}
        </div>
    </div>

</div>
{!! Form::close() !!}
