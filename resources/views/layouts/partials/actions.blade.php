<!-- resources/views/partials/actions.blade.php -->
<div class="btn-group">
    <a href="{{ route($route.'.edit', $model->id) }}" class="btn btn-sm btn-primary">Edit</a>
    <form action="{{ route($route.'.destroy', $model->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
    </form>
</div>
