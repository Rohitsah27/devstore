@extends('layouts.app')

@section('title', 'Notes')
@section('header', 'Notes Management')

@section('content')
<div x-data="{ 
    search: '',
    matches(title, tags, content) {
        const query = this.search.toLowerCase();
        if (query === '') return true;
        
        const titleMatch = title.toLowerCase().includes(query);
        const tagsMatch = tags ? tags.some(tag => tag.toLowerCase().includes(query)) : false;
        // Simple content check (optional, might be slow if content is huge)
        const contentMatch = content.toLowerCase().includes(query);
        
        return titleMatch || tagsMatch || contentMatch;
    }
}">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" x-model="search" class="w-full bg-gray-700 border border-gray-600 text-white rounded-md py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-400" placeholder="Search notes by title, tags, or content...">
        </div>
        <a href="{{ route('notes.create') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors text-center shadow-lg flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Note
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($notes as $note)
            <div x-show="matches('{{ addslashes($note['title']) }}', {{ json_encode($note['tags'] ?? []) }}, '{{ addslashes(strip_tags(Str::limit($note['content'], 200))) }}')" 
                 class="bg-gray-800 rounded-lg shadow border border-gray-700 hover:border-indigo-500 transition-colors relative group flex flex-col h-full overflow-hidden"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-semibold text-white truncate pr-4" title="{{ $note['title'] }}">{{ $note['title'] }}</h3>
                        @if(isset($note['is_pinned']) && $note['is_pinned'])
                            <svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                            </svg>
                        @endif
                    </div>
                    
                    <div class="text-gray-400 text-sm mb-4 line-clamp-3 flex-1 prose prose-invert prose-sm max-w-none">
                        {{ Str::limit(strip_tags($note['content']), 150) }}
                    </div>
                    
                    @if(isset($note['tags']) && is_array($note['tags']) && count($note['tags']) > 0)
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($note['tags'] as $tag)
                            <span class="px-2 py-1 text-xs font-medium bg-gray-700 text-gray-300 rounded-full border border-gray-600">{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center text-xs text-gray-500 mt-auto border-t border-gray-700 pt-3">
                        <span>
                            @if(isset($note['created_at']) && $note['created_at'] instanceof \DateTime)
                                {{ $note['created_at']->format('M d, Y') }}
                            @else
                                {{-- Fallback if something went wrong --}}
                                Recent
                            @endif
                        </span>
                        
                        <div class="flex space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('notes.edit', $note['id']) }}" class="text-indigo-400 hover:text-indigo-300 font-medium flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('notes.destroy', $note['id']) }}" method="POST" onsubmit="return confirm('Delete this note?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500 bg-gray-800 rounded-lg border border-gray-700 border-dashed" x-show="search === ''">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-lg">No notes found.</p>
                <p class="mt-2">Create your first note to get started!</p>
                <a href="{{ route('notes.create') }}" class="mt-4 inline-flex items-center text-indigo-400 hover:text-indigo-300">
                    Create Note &rarr;
                </a>
            </div>
            <div class="col-span-full text-center py-12 text-gray-500" x-show="search !== ''" style="display: none;">
                <p class="text-lg">No notes match your search.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
