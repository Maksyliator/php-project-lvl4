{{ Form::label('name', __('Name')) }}
@if (!$errors->has('name'))
    {{ Form::text('name', old('$taskStatus->name'), ['class' => 'form-control my-2']) }}
@else
    @error('name')
    {{ Form::text('name', old('$taskStatus->name'), ['class' => 'form-control is-invalid']) }}
    <div class="invalid-feedback"> {{ $message }}</div>
    @enderror
@endif

{{ Form::label('description', __('Description')) }}
{{ Form::textarea('description', old('$task->description'), ['class' => 'form-control my-2', 'cols' => '50', 'rows' => '10']) }}

{{ Form::label('status_id', __('Status')), ['class' => "form-group"] }}
@if (!$errors->has('status_id'))
    {{ Form::select('status_id', ['' => '----------'] + $taskStatuses->pluck('name', 'id')->all(), null, ['class' =>"form-control my-2", 'id' =>"status_id", 'name' =>"status_id"]) }}
@else
    @error('name')
    {{ Form::select('status_id', ['' => '----------'] + $taskStatuses->pluck('name', 'id')->all(), null, ['class' =>"form-control is-invalid", 'id' =>"status_id", 'name' =>"status_id"]) }}
    <div class="invalid-feedback"> {{ $message }}</div>
    @enderror
@endif

{{ Form::label('assigned_to_id', __('Assigned To')), ['class' => "form-group"] }}
{{ Form::select('assigned_to_id', ['' => '----------'] + $users->pluck('name', 'id')->all(), null, ['class' =>"form-control my-2", 'id' =>"assigned_to_id", 'name' =>"assigned_to_id"]) }}
