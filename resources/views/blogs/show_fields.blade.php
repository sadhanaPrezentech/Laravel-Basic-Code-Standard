<!-- Title Field -->
<div class="col-sm-3">
    {!! Form::label('title', 'Title') !!}
    <p>{{ $blog->name }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-3">
    {!! Form::label('description', 'Description') !!}
    <p>{{ $blog->description }}</p>
</div>

<!-- Createdby Field -->
<div class="col-sm-3">
    {!! Form::label('createdBy', 'Created By') !!}
    <p>{{ $blog->createdByUser->name }}</p>
</div>

<!-- Createddate Field -->
<div class="col-sm-3">
    {!! Form::label('created_at', 'Created Date') !!}

    <p>{{ FunctionHelper::fromSqlDateTime($blog->created_at->toDateTimeString(), true, 'd-m-Y')??''}}</p>
</div>

