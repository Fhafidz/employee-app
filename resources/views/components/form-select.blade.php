@props(['name', 'label', 'options' => [], 'value' => '', 'required' => false, 'placeholder' => 'Pilih opsi...', 'taggable' => false])

<div class="form-group">
    <label for="{{ $name }}" class="block text-sm font-semibold text-secondary mb-2">
        {{ $label }}
        @if($required)
            <span class="text-accent">*</span>
        @endif
    </label>
    <select 
        id="{{ $name }}"
        name="{{ $name }}"
        class="select2 @if($taggable) select2-tags @endif"
        data-placeholder="{{ $placeholder }}"
        @if($required) required @endif
    >
        <option></option>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @if($value == $optionValue) selected @endif>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    <span class="error hidden" data-field="{{ $name }}"></span>
</div>
