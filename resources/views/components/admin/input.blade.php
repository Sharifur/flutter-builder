@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'required' => false,
    'help' => null
])

<div>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 {{ $required ? 'required' : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="{{ $label ? 'mt-1' : '' }}">
        @if($type === 'textarea')
            <textarea {{ $attributes->merge([
                'class' => 'flex h-20 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 ' . 
                          ($error ? 'border-red-300' : 'border-gray-300')
            ]) }}>{{ $slot }}</textarea>
        @else
            <input type="{{ $type }}" {{ $attributes->merge([
                'class' => 'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 ' . 
                          ($error ? 'border-red-300' : 'border-gray-300')
            ]) }}>
        @endif
    </div>
    
    @if($help)
        <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>