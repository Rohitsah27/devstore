@extends('layouts.app')

@section('title', 'Feature Ideas')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Feature Ideas</h1>
            <p class="text-gray-400 mt-1">Track and prioritize your project roadmap</p>
        </div>
        <a href="{{ route('features.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Feature
        </a>
    </div>

    @php
        $statuses = [
            'New' => ['bg' => 'bg-blue-900/30', 'border' => 'border-blue-700/50', 'text' => 'text-blue-400', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
            'In Progress' => ['bg' => 'bg-yellow-900/30', 'border' => 'border-yellow-700/50', 'text' => 'text-yellow-400', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            'On Hold' => ['bg' => 'bg-gray-700/30', 'border' => 'border-gray-600/50', 'text' => 'text-gray-400', 'icon' => 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            'Completed' => ['bg' => 'bg-green-900/30', 'border' => 'border-green-700/50', 'text' => 'text-green-400', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z']
        ];

        $priorityColors = [
            'High' => 'bg-red-900 text-red-300 border-red-700',
            'Medium' => 'bg-yellow-900 text-yellow-300 border-yellow-700',
            'Low' => 'bg-green-900 text-green-300 border-green-700',
        ];
    @endphp

    <!-- Kanban Board -->
    <div class="flex-1 overflow-x-auto pb-4">
        <div class="flex flex-col md:flex-row gap-6 min-w-full md:min-w-0 h-full">
            @foreach($statuses as $statusName => $style)
                <div class="flex-1 min-w-[280px] flex flex-col h-full bg-gray-800/50 rounded-lg border border-gray-700 backdrop-blur-sm">
                    <!-- Column Header -->
                    <div class="p-3 border-b border-gray-700 flex items-center justify-between sticky top-0 bg-gray-800/95 z-10 rounded-t-lg backdrop-blur">
                        <div class="flex items-center gap-2">
                            <span class="{{ $style['text'] }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $style['icon'] }}"></path>
                                </svg>
                            </span>
                            <h3 class="font-semibold text-gray-200">{{ $statusName }}</h3>
                        </div>
                        <span class="bg-gray-700 text-gray-300 text-xs font-medium px-2 py-0.5 rounded-full">
                            {{ count(array_filter($features, fn($f) => ($f['status'] ?? 'New') === $statusName)) }}
                        </span>
                    </div>

                    <!-- Cards Container -->
                    <div class="p-3 space-y-3 overflow-y-auto flex-1 custom-scrollbar">
                        @forelse(array_filter($features, fn($f) => ($f['status'] ?? 'New') === $statusName) as $feature)
                            <div class="group bg-gray-800 border border-gray-700 hover:border-indigo-500 rounded-md p-3 shadow-sm transition-all hover:shadow-md relative">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-xs font-medium px-2 py-0.5 rounded border {{ $priorityColors[$feature['priority'] ?? 'Low'] }}">
                                        {{ $feature['priority'] ?? 'Low' }}
                                    </span>
                                    <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('features.edit', $feature['id']) }}" class="text-gray-400 hover:text-white p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('features.destroy', $feature['id']) }}" method="POST" onsubmit="return confirm('Delete this feature?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-400 p-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <h4 class="text-sm font-semibold text-white mb-1 leading-snug">{{ $feature['title'] }}</h4>
                                <p class="text-xs text-gray-400 line-clamp-3 mb-2">{{ $feature['description'] }}</p>
                                
                                <div class="text-[10px] text-gray-500 flex justify-end">
                                    {{ isset($feature['created_at']) && $feature['created_at'] instanceof DateTime ? $feature['created_at']->format('M d') : '' }}
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 border-2 border-dashed border-gray-700 rounded-md">
                                <p class="text-xs text-gray-500">No features</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
