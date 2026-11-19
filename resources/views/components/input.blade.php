@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'hint' => null])
@php($fieldId = $attributes->get('id', $name))

<div>
    @if($label)
        <label for="{{ $fieldId }}" class="cu-label">{{ $label }}</label>
    @endif
    <input
        id="{{ $fieldId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
        @if($errors->has($name)) aria-describedby="{{ $fieldId }}-error" @elseif($hint) aria-describedby="{{ $fieldId }}-hint" @endif
        {{ $attributes->merge(['class' => 'cu-field']) }}>
    @if($hint && !$errors->has($name))
        <p id="{{ $fieldId }}-hint" class="mt-1.5 text-xs text-slate-600">{{ $hint }}</p>
    @endif
    @error($name)
        <p id="{{ $fieldId }}-error" class="mt-1.5 text-sm text-rose-800">{{ $message }}</p>
    @enderror
</div>
