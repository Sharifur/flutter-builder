@props(['options' => [], 'placeholder' => null, 'error' => null])

<select {{ $attributes->merge([
    'class' => 'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 ' . 
              ($error ? 'border-red-300' : 'border-gray-300')
]) }}>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    
    @foreach($options as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
    
    {{ $slot }}
</select>