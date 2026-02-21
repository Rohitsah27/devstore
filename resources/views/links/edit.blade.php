@extends('layouts.app')

@section('title', 'Edit Link')
@section('header', 'Edit Link')

@section('content')
<div class="max-w-3xl mx-auto bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
    <div class="px-6 py-4 border-b border-gray-700 bg-gray-900/50">
        <h2 class="text-xl font-bold text-white">Edit Link</h2>
    </div>
    
    <form action="{{ route('links.update', $link['id']) }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1">
                <label for="title" class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                <input type="text" name="title" id="title" value="{{ $link['title'] ?? '' }}" required class="w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            </div>

            <div class="col-span-1">
                <label for="category" class="block text-sm font-medium text-gray-300 mb-1">Category</label>
                <input type="text" name="category" id="category" value="{{ $link['category'] ?? '' }}" required list="categories" class="w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                <datalist id="categories">
                    <option value="Development">
                    <option value="Design">
                    <option value="Marketing">
                    <option value="Productivity">
                    <option value="Learning">
                    <option value="Tools">
                </datalist>
            </div>
        </div>

        <div>
            <label for="url" class="block text-sm font-medium text-gray-300 mb-1">URL</label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-600 bg-gray-800 text-gray-400 text-sm">
                    Link
                </span>
                <input type="url" name="url" id="url" value="{{ $link['url'] ?? '' }}" required class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md bg-gray-700 border border-gray-600 text-white focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Description</label>
            <textarea name="description" id="description" rows="4" class="w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">{{ $link['description'] ?? '' }}</textarea>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-700 space-x-3">
            <a href="{{ route('links.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-300 bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Update Link
            </button>
        </div>
    </form>
</div>
@endsection
