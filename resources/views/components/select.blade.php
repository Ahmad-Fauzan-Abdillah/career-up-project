@props(['name', 'label' => null, 'options' => [], 'selected' => null, 'placeholder' => null])
@php($fieldId = $attributes->get('id', $name))

<div>
    @if($label)
        <label for="{{ $fieldId }}" class="cu-label">{{ $label }}</label>
    @endif
    <select
        id="{{ $fieldId }}"
        name="{{ $name }}"
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
        @if($errors->has($name)) aria-describedby="{{ $fieldId }}-error" @endif
        {{ $attributes->merge(['class' => 'cu-field']) }}>
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $label)
            <option value="{{ $value }}" @selected((string) old($name, $selected) === (string) $value)>{{ $label }}</option>
        @endforeach
        {{ $slot }}
    </select>
    @error($name)
        <p id="{{ $fieldId }}-error" class="mt-1.5 text-sm text-rose-800">{{ $message }}</p>
    @enderror
</div>
