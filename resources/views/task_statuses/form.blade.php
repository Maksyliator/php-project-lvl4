
{{ Form::label('name', __('Name')) }}
@if (!$errors->has('name'))
    {{ Form::text('name', old('$taskStatus->name'), ['class' => 'form-control my-2']) }}
@else
    @error('name')
    {{ Form::text('name', old('$taskStatus->name'), ['class' => 'form-control is-invalid']) }}
    <div class="invalid-feedback"> {{ $message }}</div>
    @enderror
@endif
