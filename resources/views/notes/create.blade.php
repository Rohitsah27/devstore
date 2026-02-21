@extends('layouts.app')

@section('title', 'Create Note')
@section('header', 'Create New Note')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.css">
<style>
    .CodeMirror {
        background-color: #374151; /* gray-700 */
        border-color: #4b5563; /* gray-600 */
        color: #d1d5db; /* gray-300 */
        border-radius: 0.375rem;
    }
    .CodeMirror-cursor {
        border-color: #d1d5db;
    }
    .editor-toolbar {
        background-color: #1f2937; /* gray-800 */
        border-color: #4b5563;
        opacity: 1;
        border-radius: 0.375rem 0.375rem 0 0;
    }
    .editor-toolbar a {
        color: #d1d5db !important;
    }
    .editor-toolbar a:hover, .editor-toolbar a.active {
        background-color: #374151 !important;
        border-color: #4b5563 !important;
    }
    .editor-preview {
        background-color: #1f2937;
        color: #d1d5db;
    }
    .editor-preview strong {
        font-weight: bold;
        color: #fff;
    }
    .editor-preview em {
        font-style: italic;
    }
    .editor-preview h1, .editor-preview h2, .editor-preview h3, .editor-preview h4, .editor-preview h5, .editor-preview h6 {
        font-weight: bold;
        margin-top: 1em;
        margin-bottom: 0.5em;
        color: #fff;
    }
    .editor-preview h1 { font-size: 2em; border-bottom: 1px solid #4b5563; padding-bottom: 0.3em; }
    .editor-preview h2 { font-size: 1.5em; border-bottom: 1px solid #4b5563; padding-bottom: 0.3em; }
    .editor-preview h3 { font-size: 1.25em; }
    .editor-preview ul, .editor-preview ol {
        padding-left: 2em;
        margin-bottom: 1em;
        list-style: inherit;
    }
    .editor-preview ul { list-style-type: disc; }
    .editor-preview ol { list-style-type: decimal; }
    .editor-preview blockquote {
        border-left: 4px solid #4b5563;
        padding-left: 1em;
        color: #9ca3af;
        margin-bottom: 1em;
    }
    .editor-preview code {
        background-color: #374151;
        padding: 0.2em 0.4em;
        border-radius: 0.25em;
        font-family: monospace;
    }
    .editor-preview pre {
        background-color: #374151;
        padding: 1em;
        border-radius: 0.375rem;
        overflow-x: auto;
        margin-bottom: 1em;
    }
    .editor-preview pre code {
        background-color: transparent;
        padding: 0;
    }
    .editor-preview a {
        color: #6366f1; /* indigo-500 */
        text-decoration: underline;
    }
    .editor-preview p {
        margin-bottom: 1em;
    }
    .editor-preview img {
        max-width: 100%;
        border-radius: 0.375rem;
    }
    .editor-preview hr {
        border-color: #4b5563;
        margin: 2em 0;
    }
    .editor-preview table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1em;
    }
    .editor-preview th, .editor-preview td {
        border: 1px solid #4b5563;
        padding: 0.5em;
    }
    .editor-preview th {
        background-color: #374151;
        font-weight: bold;
    }
    .editor-statusbar {
        color: #9ca3af;
    }
    
    /* Syntax Highlighting for Dark Mode Editor */
    .cm-header { color: #fff; font-weight: bold; }
    .cm-header-1 { font-size: 1.5em; }
    .cm-header-2 { font-size: 1.3em; }
    .cm-header-3 { font-size: 1.2em; }
    .cm-strong { color: #fff; font-weight: bold; }
    .cm-em { font-style: italic; }
    .cm-link { color: #818cf8; text-decoration: underline; }
    .cm-url { color: #9ca3af; }
    .cm-quote { color: #9ca3af; font-style: italic; }
    .cm-code { font-family: monospace; background-color: #4b5563; border-radius: 2px; padding: 0 2px; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
    <div class="px-6 py-4 border-b border-gray-700 bg-gray-900/50">
        <h2 class="text-xl font-bold text-white">New Note Details</h2>
    </div>
    
    <form action="{{ route('notes.store') }}" method="POST" class="p-6 space-y-6">
        @csrf
        
        <div>
            <label for="title" class="block text-sm font-medium text-gray-300 mb-1">Title</label>
            <input type="text" name="title" id="title" required placeholder="Enter note title..." class="w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
        </div>

        <div>
            <label for="content" class="block text-sm font-medium text-gray-300 mb-1">Content (Markdown)</label>
            <textarea name="content" id="content" class="hidden"></textarea>
        </div>

        <div>
            <label for="tags" class="block text-sm font-medium text-gray-300 mb-1">Tags</label>
            <input type="text" name="tags" id="tags" placeholder="laravel, react, bug fix" class="w-full bg-gray-700 border border-gray-600 rounded-md shadow-sm py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            <p class="mt-1 text-xs text-gray-500">Separate tags with commas.</p>
        </div>

        <div class="flex items-center">
            <div class="flex items-center h-5">
                <input id="is_pinned" name="is_pinned" type="checkbox" value="1" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-600 rounded bg-gray-700">
            </div>
            <div class="ml-3 text-sm">
                <label for="is_pinned" class="font-medium text-gray-300">Pin to top</label>
                <p class="text-gray-500">Important notes will appear first.</p>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-700 space-x-3">
            <a href="{{ route('notes.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-300 bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Save Note
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/simplemde@1.11.2/dist/simplemde.min.js"></script>
<script>
    var simplemde = new SimpleMDE({ 
        element: document.getElementById("content"),
        spellChecker: false,
        status: false,
        placeholder: "Write your note here...",
    });
</script>
@endpush
