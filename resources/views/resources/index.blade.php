@extends('layouts.app')

@section('title', 'Resource Library')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Resource Library</h1>
            <p class="text-gray-400 mt-1">Manage your project files, assets, and documents</p>
        </div>
        <a href="{{ route('resources.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
            </svg>
            Upload Resource
        </a>
    </div>

    <!-- Search & Filter -->
    <div x-data="{ 
        search: '',
        matches(title, description, type) {
            const query = this.search.toLowerCase();
            if (query === '') return true;
            
            return title.toLowerCase().includes(query) || 
                   (description && description.toLowerCase().includes(query)) ||
                   type.toLowerCase().includes(query);
        }
    }">
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" x-model="search" class="w-full bg-gray-700 border border-gray-600 text-white rounded-md py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-400" placeholder="Search resources by title, description, or type...">
        </div>

        <!-- Resources Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($resources as $resource)
                @php
                    $icon = 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'; // Default file
                    $color = 'text-gray-400';
                    $type = strtolower($resource['file_type'] ?? '');
                    
                    if (str_contains($type, 'image')) {
                        $icon = 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z';
                        $color = 'text-purple-400';
                    } elseif (str_contains($type, 'pdf')) {
                        $icon = 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z M12 11v4m-2-2h4'; // Simplified PDF
                        $color = 'text-red-400';
                    } elseif (str_contains($type, 'zip') || str_contains($type, 'compressed') || str_contains($type, 'tar')) {
                        $icon = 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4';
                        $color = 'text-yellow-400';
                    } elseif (str_contains($type, 'text') || str_contains($type, 'code') || str_contains($type, 'javascript') || str_contains($type, 'json')) {
                        $icon = 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4';
                        $color = 'text-blue-400';
                    }
                @endphp

                <div x-show="matches('{{ addslashes($resource['title']) }}', '{{ addslashes($resource['description'] ?? '') }}', '{{ addslashes($resource['file_type'] ?? '') }}')" 
                     class="bg-gray-800 rounded-lg shadow border border-gray-700 hover:border-indigo-500 transition-colors flex flex-col overflow-hidden group"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    
                    <div class="p-5 flex-1">
                        <div class="flex items-start justify-between">
                            <div class="flex-shrink-0 {{ $color }}">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"></path>
                                </svg>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex items-start space-x-2">
                                <a href="{{ $resource['file_url'] }}" target="_blank" class="text-gray-400 hover:text-white transition-colors" title="Download">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('resources.destroy', $resource['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h3 class="text-lg font-medium text-white truncate" title="{{ $resource['title'] }}">
                                {{ $resource['title'] }}
                            </h3>
                            @if(!empty($resource['description']))
                                <p class="mt-1 text-sm text-gray-400 line-clamp-2" title="{{ $resource['description'] }}">
                                    {{ $resource['description'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="bg-gray-700/50 px-5 py-3 border-t border-gray-700 flex items-center justify-between text-xs text-gray-400">
                        <span>
                            @if(isset($resource['size']))
                                {{ round($resource['size'] / 1024, 2) }} KB
                            @else
                                Unknown Size
                            @endif
                        </span>
                        <span>{{ isset($resource['created_at']) && $resource['created_at'] instanceof DateTime ? $resource['created_at']->format('M d, Y') : '' }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-300">No resources found</h3>
                    <p class="mt-1 text-sm text-gray-500">Upload your first file to get started.</p>
                    <div class="mt-6">
                        <a href="{{ route('resources.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload Resource
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
