@section('third_party_stylesheets')
@endsection
<div class="row">
    <!-- Name Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('name', 'Title') !!}
        {!! Form::text('name', null, ['placeholder'=>'Enter Title','class' => 'form-control '. ($errors->has('name') ? 'is-invalid' : '')]) !!}
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Description Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('description', 'Description') !!}
        {!! Form::textarea('description', null, ['id' => 'description', 'placeholder' => 'Enter Description', 'class' => 'form-control '. ($errors->has('description') ? 'is-invalid' : '')]) !!}
       @error('description')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Tag Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('tag', 'Tag') !!}
        {!! Form::text('tag', null, ['id' => 'tag', 'placeholder' => 'Enter Tag', 'class' => 'form-control '. ($errors->has('tag') ? 'is-invalid' : '')]) !!}
       @error('tag')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

</div>
@section('third_party_scripts')
@endsection
