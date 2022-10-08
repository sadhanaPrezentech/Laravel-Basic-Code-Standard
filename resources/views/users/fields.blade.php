@section('third_party_stylesheets')
@endsection
<div class="row">
    <!-- Role Field -->
    <div class="form-group col-sm-12">
        {!! Form::select('role', $roleItems, null, ['class' => 'form-control '. ($errors->has('role') ? 'is-invalid' : ''), 'placeholder' => 'Select Role','id' => 'select_role']) !!}
         @error('role')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <!-- Name Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('name', 'Name') !!}
        {!! Form::text('name', null, ['class' => 'form-control '. ($errors->has('name') ? 'is-invalid' : ''),'placeholder'=>'Enter Name']) !!}
       @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Email Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('email', 'Email') !!}
        {!! Form::email('email', null, ['id' => 'email', 'class' => 'form-control '. ($errors->has('email') ? 'is-invalid' : ''),'placeholder'=>'Enter Email']) !!}
         @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Password Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('password', 'Password') !!}
       {!! Form::password('password', ['class' => 'form-control '. ($errors->has('password') ? 'is-invalid' : ''),'placeholder'=>'Enter Password']) !!}
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <!-- Confirm Password Field -->
    <div class="form-group col-sm-12">
        {!! Form::label('password_confirmation', 'Confirm Password') !!}
        {!! Form::password('password_confirmation', ['class' => 'form-control '. ($errors->has('password_confirmation') ? 'is-invalid' : ''),'placeholder'=>'Enter Confirmation Password']) !!}
        @error('password_confirmation')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

</div>
@section('third_party_scripts')
@endsection
