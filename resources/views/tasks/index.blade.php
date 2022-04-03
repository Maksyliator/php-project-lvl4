@extends('layouts.app')

@section('h1')
    {{ __('Tasks') }}
@endsection

@section('content')
    <main class="container py-4">
    <div class="d-flex mb-3">
            {{ Form::open(['url' => route('tasks.index'), 'method' => 'get']) }}
            {{ Form::select('status_id', ['' => __('Status')] + $taskStatuses->pluck('name', 'id')->all(), null, ['class' =>"form-select me-2", 'id' =>"status_id", 'name' =>"filter[status_id]"]) }}
            {{ Form::select('created_by_id', ['' => __('Author')] + $users->pluck('name', 'id')->all(), null, ['class' =>"form-select me-2", 'id' =>"created_by_id", 'name' =>"filter[created_by_id]"]) }}
            {{ Form::select('assigned_to_id', ['' => __('Assigned To')] + $users->pluck('name', 'id')->all(), null, ['class' =>"form-select me-2", 'id' =>"assigned_to_id", 'name' =>"filter[assigned_to_id]"]) }}
            {{ Form::submit(__('Apply'), ['class' => "btn btn-outline-primary mr-2"]) }}
            {{ Form::close() }}
        @auth
        <a href="{{ route('tasks.create')}}" class="btn btn-primary ml-auto">
            {{ __('Create task') }}
        </a>
        @endauth
    </div>
    <table class="table me-2">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Creator') }}</th>
                <th>{{ __('Assigned To') }}</th>
                <th>{{ __('Created at') }}</th>
                @auth
                <th>{{ __('Actions') }}</th>
                @endauth
            </tr>
        </thead>
    <tbody>
        @foreach ($tasks as $task)
        <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->status->name }}</td>
            <td><a href="{{ route('tasks.show', $task->id)}}">{{ $task->name }}</a></td>
            <td>{{ $task->createdBy->name }}</td>
            <td>{{ optional($task->assignedTo)->name }}</td>
            <td>{{ date('d.m.Y', strtotime($task->created_at)) }}</td>
            @auth
            <td>
                @can('delete', $task)

                    <a class="text-danger" data-method="DELETE" href="{{ route('tasks.destroy', $task) }}" data-confirm="{{ __('Аre you sure?') }}"  rel="nofollow">
                        {{ __('Delete') }}
                    </a>
                @endcan
                    <a href="{{ route('tasks.edit', $task->id) }}" rel="nofollow">
                        {{ __('Change') }}
                    </a>
            </td>
            @endauth
        </tr>
        @endforeach
    </tbody>
    </table>
    </main>
    <nav aria-label="navigation">
    {{ $tasks->links("pagination::bootstrap-4") }}
    </nav>
@endsection
