@php
    $fieldLabel = $label ?? $field['label'];
    $fieldColumn = $columnClass ?? ($field['type'] === 'textarea' ? 'col-12' : 'col-lg-6');
@endphp

<div class="{{ $fieldColumn }}">
    <label for="{{ $field['key'] }}" class="form-label fw-semibold">{{ $fieldLabel }}</label>
    @if($field['type'] === 'textarea')
        <textarea
            id="{{ $field['key'] }}"
            name="content[{{ $field['key'] }}]"
            rows="{{ $rows ?? 3 }}"
            maxlength="{{ $field['max'] }}"
            class="form-control @error('content.'.$field['key']) is-invalid @enderror"
        >{{ old('content.'.$field['key'], $content[$field['key']] ?? $field['fallback']) }}</textarea>
    @else
        <input
            id="{{ $field['key'] }}"
            name="content[{{ $field['key'] }}]"
            type="text"
            maxlength="{{ $field['max'] }}"
            value="{{ old('content.'.$field['key'], $content[$field['key']] ?? $field['fallback']) }}"
            class="form-control @error('content.'.$field['key']) is-invalid @enderror"
        >
    @endif
    @error('content.'.$field['key'])
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
