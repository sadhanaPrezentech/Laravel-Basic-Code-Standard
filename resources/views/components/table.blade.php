@section('third_party_stylesheets')
    @include('layouts.datatables_css')
@endsection

@includeFirst(
    [$entity['view'].'.search', 'components.search'],
    ['form_id' => isset($entity['targetModel']) ? "search-{$entity['targetModel']}" : 'search-form']
)
{!! $dataTable->table(['width' => '100%', 'class' => 'table theme-table', 'id' => isset($entity['targetModel']) ? "{$entity['targetModel']}-datatable" : 'dataTableBuilder']) !!}

@section('third_party_scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}
    @include('vendor.datatables.search', [
        'id' => isset($entity['targetModel']) ? "{$entity['targetModel']}-datatable" : 'dataTableBuilder',
        'form_id' => isset($entity['targetModel']) ? "search-{$entity['targetModel']}" : 'search-form',
    ])
@endsection

