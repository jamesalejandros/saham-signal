@props(['value'])

<label {{ $attributes->merge(['class' => 'ss-label']) }}>
    {{ $value ?? $slot }}
</label>
