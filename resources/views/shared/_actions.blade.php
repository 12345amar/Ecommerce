@can('view_users')
    <a href="{{ route('users.show', [str_singular('users') => $id])  }}" class="btn btn-xs btn-default">
    <i class="fa fa-eye"></i> View</a>
@endcan

@can('edit_'.$entity)
    <a href="{{ route($entity.'.edit', [str_singular($entity) => $id])  }}" class="btn btn-xs btn-info">
        <i class="fa fa-edit"></i> Edit</a>
@endcan

@can('delete_'.$entity)
    {!! Form::open( ['method' => 'delete', 'url' => route($entity.'.destroy', ['user' => $id]), 'style' => 'display: inline', 'onSubmit' => 'return confirm("Are yous sure wanted to delete it?")']) !!}
        <button type="submit" class="btn-delete btn btn-xs btn-danger">
            <i class="glyphicon glyphicon-trash"></i>
        </button>
    {!! Form::close() !!}
@endcan
