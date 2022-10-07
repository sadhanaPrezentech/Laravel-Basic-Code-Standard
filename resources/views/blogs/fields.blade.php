@section('third_party_stylesheets')
@endsection
<div class="row">
    <!-- Name Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('name', 'Title') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        <span class="help-block"></span>
    </div>

    <!-- Description Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('description', 'Description') !!}
        {!! Form::textarea('description', null, ['id' => 'desciption']) !!}
        <span class="help-block"></span>
    </div>

    <!-- Tag Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('tag', 'Tag') !!}
        {!! Form::text('tag', null, ['id' => 'tag']) !!}
        <span class="help-block"></span>
    </div>

</div>
@section('third_party_scripts')
@endsection
