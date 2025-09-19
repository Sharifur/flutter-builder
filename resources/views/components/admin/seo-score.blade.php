@props(['score' => 0, 'grade' => 'poor', 'checks' => [], 'suggestions' => []])

@php
    $gradeColors = [
        'excellent' => 'text-green-600 bg-green-100 border-green-200',
        'good' => 'text-blue-600 bg-blue-100 border-blue-200',
        'average' => 'text-yellow-600 bg-yellow-100 border-yellow-200',
        'poor' => 'text-orange-600 bg-orange-100 border-orange-200',
        'critical' => 'text-red-600 bg-red-100 border-red-200'
    ];
    
    $progressColors = [
        'excellent' => 'bg-green-500',
        'good' => 'bg-blue-500',
        'average' => 'bg-yellow-500',
        'poor' => 'bg-orange-500',
        'critical' => 'bg-red-500'
    ];
@endphp

<div class="bg-white rounded-lg border p-4">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">SEO Score</h3>
        <div class="flex items-center space-x-3">
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-900">{{ $score }}/100</div>
                <div class="text-sm {{ explode(' ', $gradeColors[$grade])[0] }} font-medium capitalize">
                    {{ $grade }}
                </div>
            </div>
            <div class="w-16 h-16 relative">
                <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                    <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="transparent"
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                    <path class="{{ $progressColors[$grade] }}" stroke="currentColor" stroke-width="3" fill="transparent"
                          stroke-dasharray="{{ $score }}, 100"
                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-sm font-bold">{{ $score }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Checks -->
    @if(count($checks) > 0)
        <div class="space-y-2 mb-4">
            @foreach($checks as $check => $data)
                <div class="flex items-center space-x-2">
                    @if($data['status'] === 'good')
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($data['status'] === 'warning')
                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                    <span class="text-sm text-gray-700">{{ $data['message'] ?? 'Status check' }}</span>
                </div>
            @endforeach
        </div>
    @endif
    
    <!-- Suggestions -->
    @if(count($suggestions) > 0)
        <div class="border-t pt-4">
            <h4 class="text-sm font-medium text-gray-900 mb-2">Suggestions:</h4>
            <ul class="space-y-1">
                @foreach($suggestions as $suggestion)
                    <li class="text-sm text-gray-600 flex items-start space-x-2">
                        <span class="text-blue-500 mt-1">•</span>
                        <span>{{ $suggestion }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>