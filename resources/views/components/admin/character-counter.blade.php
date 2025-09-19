@props(['current' => 0, 'optimal' => null, 'max' => null, 'target' => null])

@php
    $currentLength = is_string($current) ? strlen($current) : $current;
    $status = 'text-gray-500';
    $bgColor = 'bg-gray-100';
    $progressColor = 'bg-gray-400';
    
    if ($optimal) {
        [$optimalMin, $optimalMax] = is_array($optimal) ? $optimal : [$optimal - 10, $optimal + 10];
        if ($currentLength >= $optimalMin && $currentLength <= $optimalMax) {
            $status = 'text-green-600';
            $bgColor = 'bg-green-100';
            $progressColor = 'bg-green-500';
        } elseif ($currentLength > 0 && $currentLength <= ($max ?? $optimalMax + 20)) {
            $status = 'text-yellow-600';
            $bgColor = 'bg-yellow-100';
            $progressColor = 'bg-yellow-500';
        } elseif ($currentLength > ($max ?? $optimalMax + 20)) {
            $status = 'text-red-600';
            $bgColor = 'bg-red-100';
            $progressColor = 'bg-red-500';
        }
    }
    
    $percentage = $max ? min(($currentLength / $max) * 100, 100) : 0;
@endphp

<div class="flex items-center space-x-2 text-sm {{ $status }}">
    <span>{{ $currentLength }}</span>
    
    @if($optimal)
        <span class="text-gray-400">/</span>
        <span class="text-gray-500">
            @if(is_array($optimal))
                {{ $optimal[0] }}-{{ $optimal[1] }}
            @else
                {{ $optimal }}
            @endif
            optimal
        </span>
    @endif
    
    @if($max)
        <span class="text-gray-400">/</span>
        <span class="text-gray-500">{{ $max }} max</span>
        
        <!-- Progress Bar -->
        <div class="w-20 h-2 {{ $bgColor }} rounded-full overflow-hidden">
            <div class="h-full {{ $progressColor }} transition-all duration-200" 
                 style="width: {{ $percentage }}%"></div>
        </div>
    @endif
</div>