@section('third_party_stylesheets')
@endsection
<div class="row">
    <!-- Name Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('name', 'Name') !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
        <span class="help-block"></span>
    </div>

    <!-- Email Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('email', 'Email') !!}
        {!! Form::email('email', null, ['id' => 'email']) !!}
        <span class="help-block"></span>
    </div>

    <!-- Password Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('password', 'Password') !!}
        {!! Form::password('password', null, ['id' => 'password']) !!}
        <span class="help-block"></span>
    </div>

    <!-- Confirm Password Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('password_confirmation', 'Confirm Password') !!}
        {!! Form::password('password_confirmation', null, ['id' => 'password_confirmation']) !!}
        <span class="help-block"></span>
    </div>

</div>
@section('third_party_scripts')
@endsection
