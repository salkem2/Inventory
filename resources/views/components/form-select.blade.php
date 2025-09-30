@props([
    'name',
    'label' => null,
    'value' => null,
    'disabled' => false,
    'optionData' => null,
    'optionValue' => null,
    'optionLabel' => null,
])

@if ($label)
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
    </label>
@endif

@php
    $value = old($name, $value ?? '');
@endphp

<select name="{{ $name }}" id="{{ $name }}" class="form-select @error($name) is-invalid @enderror"
        @disabled($disabled)>
    <option value="">Pilih {{ $label }} :</option>

  @foreach ($optionData as $item)
    @php
        if(is_array($item)) {
            $val = $item[$optionValue];
            $label = $item[$optionLabel];
        } else {
            $val = $item->{$optionValue} ?? $item;
            $label = $item->{$optionLabel} ?? $item;
        }
    @endphp
    <option value="{{ $val }}" @if ($value == $val) selected @endif>
        {{ $label }}
    </option>
@endforeach

</select>

@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror