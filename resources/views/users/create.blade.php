@extends('layouts.admin')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Create {!!$entity['singular']!!}</h1>
                </div>
            </div>
        </div>
    </section>
    <div class="content px-3">
        @include('flash::message')
        <div class="card">
            {!! Form::open(['route' => $entity['url'].'.store', 'id' => 'frm_'.$entity['targetModel']]) !!}
            <div class="card-body">
                <div class="col-sm-8">
                @include($entity['view'].'.fields')
                </div>
            </div>
            <div class="card-footer text-right">
                @include('components.form-buttons')
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
