@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Edit {!!$entity['singular']!!}</h1>
                </div>
            </div>
        </div>
    </section>
    <div class="content px-3">
        <div class="card">
            {!! Form::model($user, ['route' => [$entity['url'].'.update', $user->id], 'method' => 'patch', 'id' => 'frm_'.$entity['targetModel']]) !!}
            <div class="card-body">
                @include($entity['view'].'.fields')
            </div>
            <div class="card-footer text-right">
                @include('components.form-buttons')
            </div>
            {!! Form::close() !!}
        </div>

    </div>
@endsection
