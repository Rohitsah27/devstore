@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6 mb-8">
    <!-- Notes Count -->
    <div class="px-4 py-5 bg-gradient-to-br from-gray-800 to-gray-700 rounded-xl shadow-lg border border-gray-600 hover:border-indigo-500 transition-all duration-300 group transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-2">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider group-hover:text-indigo-400">Notes</div>
            <div class="p-2 bg-gray-700 rounded-lg group-hover:bg-indigo-900/50 transition-colors">
                <svg class="h-4 w-4 text-gray-400 group-hover:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-white">{{ $notesCount ?? 0 }}</div>
    </div>

    <!-- Links Count -->
    <div class="px-4 py-5 bg-gradient-to-br from-gray-800 to-gray-700 rounded-xl shadow-lg border border-gray-600 hover:border-green-500 transition-all duration-300 group transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-2">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider group-hover:text-green-400">Links</div>
            <div class="p-2 bg-gray-700 rounded-lg group-hover:bg-green-900/50 transition-colors">
                <svg class="h-4 w-4 text-gray-400 group-hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
            </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-white">{{ $linksCount ?? 0 }}</div>
    </div>

    <!-- Snippets Count -->
    <div class="px-4 py-5 bg-gradient-to-br from-gray-800 to-gray-700 rounded-xl shadow-lg border border-gray-600 hover:border-yellow-500 transition-all duration-300 group transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-2">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider group-hover:text-yellow-400">Snippets</div>
            <div class="p-2 bg-gray-700 rounded-lg group-hover:bg-yellow-900/50 transition-colors">
                <svg class="h-4 w-4 text-gray-400 group-hover:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
            </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-white">{{ $snippetsCount ?? 0 }}</div>
    </div>

    <!-- Features Count -->
    <div class="px-4 py-5 bg-gradient-to-br from-gray-800 to-gray-700 rounded-xl shadow-lg border border-gray-600 hover:border-purple-500 transition-all duration-300 group transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-2">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider group-hover:text-purple-400">Ideas</div>
            <div class="p-2 bg-gray-700 rounded-lg group-hover:bg-purple-900/50 transition-colors">
                <svg class="h-4 w-4 text-gray-400 group-hover:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-white">{{ $featuresCount ?? 0 }}</div>
    </div>

    <!-- Resources Count -->
    <div class="col-span-2 sm:col-span-1 px-4 py-5 bg-gradient-to-br from-gray-800 to-gray-700 rounded-xl shadow-lg border border-gray-600 hover:border-pink-500 transition-all duration-300 group transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-2">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider group-hover:text-pink-400">Files</div>
            <div class="p-2 bg-gray-700 rounded-lg group-hover:bg-pink-900/50 transition-colors">
                <svg class="h-4 w-4 text-gray-400 group-hover:text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold text-white">{{ $resourcesCount ?? 0 }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Quick Actions -->
    <div class="lg:col-span-1 bg-gray-800 rounded-xl shadow-lg border border-gray-700 p-6 h-fit">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="h-5 w-5 mr-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
            Quick Actions
        </h3>
        <div class="grid grid-cols-2 lg:grid-cols-1 gap-3">
            <a href="{{ route('notes.create') }}" class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-4 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-all hover:shadow-md group text-center lg:text-left">
                <span class="w-10 h-10 lg:w-8 lg:h-8 flex items-center justify-center bg-indigo-900 text-indigo-200 rounded-full mb-2 lg:mb-0 lg:mr-3 text-xl lg:text-lg font-bold group-hover:bg-indigo-800 shadow-sm">+</span>
                <span class="font-medium text-sm lg:text-base">Create Note</span>
            </a>
            <a href="{{ route('links.create') }}" class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-4 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-all hover:shadow-md group text-center lg:text-left">
                <span class="w-10 h-10 lg:w-8 lg:h-8 flex items-center justify-center bg-green-900 text-green-200 rounded-full mb-2 lg:mb-0 lg:mr-3 text-xl lg:text-lg font-bold group-hover:bg-green-800 shadow-sm">+</span>
                <span class="font-medium text-sm lg:text-base">Add Link</span>
            </a>
            <a href="{{ route('snippets.create') }}" class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-4 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-all hover:shadow-md group text-center lg:text-left">
                <span class="w-10 h-10 lg:w-8 lg:h-8 flex items-center justify-center bg-yellow-900 text-yellow-200 rounded-full mb-2 lg:mb-0 lg:mr-3 text-xl lg:text-lg font-bold group-hover:bg-yellow-800 shadow-sm">+</span>
                <span class="font-medium text-sm lg:text-base">Save Snippet</span>
            </a>
            <a href="{{ route('features.create') }}" class="flex flex-col lg:flex-row items-center justify-center lg:justify-start px-4 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-all hover:shadow-md group text-center lg:text-left">
                <span class="w-10 h-10 lg:w-8 lg:h-8 flex items-center justify-center bg-purple-900 text-purple-200 rounded-full mb-2 lg:mb-0 lg:mr-3 text-xl lg:text-lg font-bold group-hover:bg-purple-800 shadow-sm">+</span>
                <span class="font-medium text-sm lg:text-base">Log Idea</span>
            </a>
            <a href="{{ route('resources.create') }}" class="col-span-2 lg:col-span-1 flex flex-col lg:flex-row items-center justify-center lg:justify-start px-4 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-all hover:shadow-md group text-center lg:text-left">
                <span class="w-10 h-10 lg:w-8 lg:h-8 flex items-center justify-center bg-pink-900 text-pink-200 rounded-full mb-2 lg:mb-0 lg:mr-3 text-xl lg:text-lg font-bold group-hover:bg-pink-800 shadow-sm">+</span>
                <span class="font-medium text-sm lg:text-base">Upload File</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="lg:col-span-2 bg-gray-800 rounded-lg shadow border border-gray-700 p-6">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center">
            <svg class="h-5 w-5 mr-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Recent Activity
        </h3>
        
        <div class="flow-root">
            <ul class="-my-4 divide-y divide-gray-700">
                @forelse($recentItems as $item)
                    <li class="py-4 hover:bg-gray-750 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($item['type'] == 'Note')
                                    <span class="h-8 w-8 rounded-full bg-indigo-900 flex items-center justify-center text-indigo-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </span>
                                @elseif($item['type'] == 'Link')
                                    <span class="h-8 w-8 rounded-full bg-green-900 flex items-center justify-center text-green-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                                    </span>
                                @elseif($item['type'] == 'Snippet')
                                    <span class="h-8 w-8 rounded-full bg-yellow-900 flex items-center justify-center text-yellow-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    </span>
                                @elseif($item['type'] == 'Feature')
                                    <span class="h-8 w-8 rounded-full bg-purple-900 flex items-center justify-center text-purple-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                                    </span>
                                @else
                                    <span class="h-8 w-8 rounded-full bg-pink-900 flex items-center justify-center text-pink-300">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">
                                    @if($item['route'])
                                        <a href="{{ route($item['route'], $item['id']) }}" class="hover:underline">{{ $item['title'] }}</a>
                                    @else
                                        {{ $item['title'] }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">
                                    Added {{ \Carbon\Carbon::createFromTimestamp($item['raw_date'])->diffForHumans() }}
                                </p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300 border border-gray-600">
                                    {{ $item['type'] }}
                                </span>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="py-8 text-center text-gray-500 italic">
                        No activity yet. Start by adding some content!
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
