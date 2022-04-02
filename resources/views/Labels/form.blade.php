{{ Form::label('name', __('Name')) }}
@if (!$errors->has('name'))
    {{ Form::text('name', old('$label->name'), ['class' => 'form-control my-2']) }}
@else
    @error('name')
    {{ Form::text('name', old('$label->name'), ['class' => 'form-control is-invalid']) }}
    <div class="invalid-feedback"> {{ $message }}</div>
    @enderror
@endif

{{ Form::label('description', __('Description')) }}
{{ Form::textarea('description', old('$label->description'), ['class' => 'form-control my-2', 'cols' => '50', 'rows' => '10']) }}

