@extends('layouts.app')

@section('title', 'Code Snippets')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
<style>
    /* Custom scrollbar for code blocks */
    pre code::-webkit-scrollbar {
        height: 8px;
        background-color: #1f2937; /* gray-800 */
    }
    pre code::-webkit-scrollbar-thumb {
        background-color: #4b5563; /* gray-600 */
        border-radius: 4px;
    }
    pre code::-webkit-scrollbar-thumb:hover {
        background-color: #6b7280; /* gray-500 */
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Code Snippets</h1>
            <p class="text-gray-400 mt-1">Store and manage your reusable code blocks</p>
        </div>
        <a href="{{ route('snippets.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Snippet
        </a>
    </div>

    <!-- Search & Filter -->
    <div x-data="{ 
        search: '',
        matches(title, language, description, code) {
            const query = this.search.toLowerCase();
            if (query === '') return true;
            
            return title.toLowerCase().includes(query) || 
                   (language && language.toLowerCase().includes(query)) || 
                   (description && description.toLowerCase().includes(query)) ||
                   code.toLowerCase().includes(query);
        }
    }">
        <div class="relative mb-6">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" x-model="search" class="w-full bg-gray-700 border border-gray-600 text-white rounded-md py-2 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-400" placeholder="Search snippets by title, language, or code...">
        </div>

        <!-- Snippets Grid -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            @forelse($snippets as $snippet)
                <div x-show="matches({{ json_encode($snippet['title']) }}, {{ json_encode($snippet['language']) }}, {{ json_encode($snippet['description'] ?? '') }}, {{ json_encode(substr($snippet['code'], 0, 100)) }})" 
                     class="bg-gray-800 rounded-lg shadow border border-gray-700 flex flex-col overflow-hidden hover:border-indigo-500 transition-colors"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    
                    <div class="p-4 border-b border-gray-700 flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-lg font-semibold text-white truncate max-w-[200px] sm:max-w-xs" title="{{ $snippet['title'] }}">
                                    {{ $snippet['title'] }}
                                </h3>
                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-700 text-indigo-400 border border-gray-600">
                                    {{ $snippet['language'] }}
                                </span>
                            </div>
                            @if(!empty($snippet['description']))
                                <p class="text-sm text-gray-400 line-clamp-1">{{ $snippet['description'] }}</p>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('snippets.edit', $snippet['id']) }}" class="text-gray-400 hover:text-white transition-colors p-1" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('snippets.destroy', $snippet['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this snippet?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="relative group flex-grow bg-[#282c34]">
                        <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            <button onclick="copyToClipboard(this, `{{ addslashes($snippet['code']) }}`)" 
                                    class="bg-gray-700 hover:bg-gray-600 text-white text-xs px-2 py-1 rounded shadow flex items-center gap-1 border border-gray-600">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Copy</span>
                            </button>
                        </div>
                        <pre class="m-0 p-0 h-full"><code class="language-{{ strtolower($snippet['language']) }} h-full text-sm block p-4 overflow-x-auto">{{ $snippet['code'] }}</code></pre>
                    </div>
                    
                    <div class="bg-gray-800 px-4 py-2 border-t border-gray-700 text-xs text-gray-500 flex justify-between items-center">
                        <span>{{ isset($snippet['created_at']) && $snippet['created_at'] instanceof DateTime ? $snippet['created_at']->format('M d, Y') : 'Unknown Date' }}</span>
                        <span>{{ strlen($snippet['code']) }} chars</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-300">No snippets found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new code snippet.</p>
                    <div class="mt-6">
                        <a href="{{ route('snippets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Snippet
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre code').forEach((el) => {
            hljs.highlightElement(el);
        });
    });

    function copyToClipboard(button, text) {
        navigator.clipboard.writeText(text).then(() => {
            const originalContent = button.innerHTML;
            button.innerHTML = `
                <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-green-400">Copied!</span>
            `;
            setTimeout(() => {
                button.innerHTML = originalContent;
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>
@endpush
