@props(['name', 'label', 'placeholder' => '', 'rows' => 2, 'value' => '', 'required' => false])

<div class="form-group">
    <label for="{{ $name }}" class="block text-sm font-semibold text-secondary mb-2">
        {{ $label }}
        @if($required)
            <span class="text-accent">*</span>
        @endif
    </label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-input"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
    >{{ $value }}</textarea>
    <span class="error hidden" data-field="{{ $name }}"></span>
</div>
