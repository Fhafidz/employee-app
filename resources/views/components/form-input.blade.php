@props(['name', 'label', 'type' => 'text', 'placeholder' => '', 'value' => '', 'required' => false, 'maxlength' => null, 'class' => ''])

<div class="form-group">
    <label for="{{ $name }}" class="block text-sm font-semibold text-secondary mb-2">
        {{ $label }}
        @if($required)
            <span class="text-accent">*</span>
        @endif
    </label>
    <input 
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-input {{ $class }}"
        placeholder="{{ $placeholder }}"
        value="{{ $value }}"
        @if($maxlength) maxlength="{{ $maxlength }}" @endif
        @if($required) required @endif
    >
    <span class="error hidden" data-field="{{ $name }}"></span>
</div>
