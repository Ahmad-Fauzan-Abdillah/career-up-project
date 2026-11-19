@props(['name', 'label' => null, 'value' => null, 'rows' => 4, 'hint' => null])
@php($fieldId = $attributes->get('id', $name))

<div>
    @if($label)
        <label for="{{ $fieldId }}" class="cu-label">{{ $label }}</label>
    @endif
    <textarea
        id="{{ $fieldId }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        aria-invalid="{{ $errors->has($name) ? 'true' : 'false' }}"
        @if($errors->has($name)) aria-describedby="{{ $fieldId }}-error" @elseif($hint) aria-describedby="{{ $fieldId }}-hint" @endif
        {{ $attributes->merge(['class' => 'cu-field']) }}>{{ old($name, $value) }}</textarea>
    @if($hint && !$errors->has($name))
        <p id="{{ $fieldId }}-hint" class="mt-1.5 text-xs text-slate-600">{{ $hint }}</p>
    @endif
    @error($name)
        <p id="{{ $fieldId }}-error" class="mt-1.5 text-sm text-rose-800">{{ $message }}</p>
    @enderror
</div>
