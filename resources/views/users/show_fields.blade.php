<!-- Name Field -->
<div class="col-sm-3">
    {!! Form::label('name', 'Name') !!}
    <p>{{ $user->name }}</p>
</div>
<!-- Email Field -->
<div class="col-sm-3">
    {!! Form::label('email', 'Email') !!}
    <p>{{ $user->email }}</p>
</div>
<!-- Created date Field -->
<div class="col-sm-3">
    {!! Form::label('created_at', 'Created Date') !!}
    <p>{{ FunctionHelper::fromSqlDateTime($user->created_at->toDateTimeString(), true, 'd-m-Y')??''}}</p>
</div>

