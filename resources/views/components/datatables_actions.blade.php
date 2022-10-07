
{!! Form::open([
    'route' => [$entity['url'] . '.destroy', $id],
    'method' => 'delete',
    'data-model' => $entity['targetModel'],
    'id' => "{$entity['targetModel']}_$id",
]) !!}
 {!! Form::hidden('process', 'delete') !!}
<div class="btn-group" role="group">
    <button type="button" class="btn btn-primary btn-sm">Action</button>
    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu dropdown-menu-right">
            <a class='dropdown-item text-secondary' href="{{ route($entity['url'] . '.edit', $id) }}"
                title="Edit {!! $entity['singular'] ?? null !!}">
                 Edit
            </a>
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item text-primary" href="{!! $url ?? route($entity['url'] . '.show', $id) !!}" title="Show {!! $entity['singular'] ?? null !!}">
                 Show
            </a>
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="javascript:submitFormByaction('delete', '{{$entity['targetModel']}}_{{$id}}', '{!! $entity['singular'] ?? null !!} Delete Successfully')" title="Delete {{$entity['singular']}}">
                Delete
            </a>

    </div>
</div>
{!! Form::close() !!}
