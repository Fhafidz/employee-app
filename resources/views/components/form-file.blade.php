@props(['name', 'label', 'accept' => '.jpg,.jpeg,.png', 'help' => '', 'class' => '', 'value' => ''])

<div class="form-group">
    @if($label)
        <label class="block text-sm font-semibold text-secondary mb-2">{{ $label }}</label>
    @endif

    <div class="file-loading">
        <input id="{{ $name }}" 
               name="{{ $name }}" 
               type="file" 
               class="file-input-js {{ $class }}" 
               accept="{{ $accept }}"
               data-initial-preview="{{ $value ? asset('storage/employees/' . $value) : '' }}">
    </div>

    @if($help)
        <p class="text-xs text-gray-400 mt-2">{{ $help }}</p>
    @endif

    <span class="error hidden" data-field="{{ $name }}"></span>
</div>
