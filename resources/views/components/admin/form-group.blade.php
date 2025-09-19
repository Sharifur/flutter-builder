@props(['label' => null, 'required' => false, 'error' => null, 'help' => null])

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    @if($label)
        <label class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div>
        {{ $slot }}
    </div>
    
    @if($help)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @endif
</div>