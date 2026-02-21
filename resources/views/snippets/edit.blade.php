@extends('layouts.app')

@section('title', 'Edit Snippet')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-white sm:text-3xl sm:truncate">Edit Snippet</h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('snippets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Back to Snippets
            </a>
        </div>
    </div>

    <div class="bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-700">
        <form action="{{ route('snippets.update', $snippet['id']) }}" method="POST" id="snippet-form" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300">Title</label>
                <input type="text" name="title" id="title" value="{{ $snippet['title'] }}" required
                       class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                       placeholder="e.g., Array Deduplication Helper">
            </div>

            <!-- Language -->
            <div>
                <label for="language" class="block text-sm font-medium text-gray-300">Language</label>
                <select name="language" id="language" required
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @foreach(['javascript' => 'JavaScript', 'typescript' => 'TypeScript', 'php' => 'PHP', 'python' => 'Python', 'html' => 'HTML', 'css' => 'CSS', 'sql' => 'SQL', 'bash' => 'Shell / Bash', 'json' => 'JSON', 'yaml' => 'YAML', 'markdown' => 'Markdown', 'java' => 'Java', 'csharp' => 'C#', 'cpp' => 'C++', 'go' => 'Go', 'rust' => 'Rust', 'plaintext' => 'Plain Text'] as $value => $label)
                        <option value="{{ $value }}" {{ $snippet['language'] == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300">Description (Optional)</label>
                <textarea name="description" id="description" rows="2"
                          class="mt-1 block w-full bg-gray-700 border border-gray-600 text-white rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                          placeholder="Briefly describe what this snippet does...">{{ $snippet['description'] ?? '' }}</textarea>
            </div>

            <!-- Code -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="code-editor" class="block text-sm font-medium text-gray-300">Code</label>
                    <button type="button" id="format-btn" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-200 bg-indigo-900 hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        Format Document
                    </button>
                </div>
                
                <div id="code-editor" class="h-96 w-full border border-gray-600 rounded-md overflow-hidden"></div>
                <input type="hidden" name="code" id="code-input">
                
                <p class="mt-2 text-sm text-gray-500">
                    Syntax highlighting and formatting provided by Monaco Editor.
                </p>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-700">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Snippet
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs/loader.min.js"></script>
<script>
    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.45.0/min/vs' }});

    require(['vs/editor/editor.main'], function() {
        var initialCode = {!! json_encode($snippet['code'], JSON_HEX_TAG) !!};
        var initialLang = "{{ $snippet['language'] == 'bash' ? 'shell' : $snippet['language'] }}";

        var editor = monaco.editor.create(document.getElementById('code-editor'), {
            value: initialCode,
            language: initialLang,
            theme: 'vs-dark',
            automaticLayout: true,
            minimap: { enabled: false },
            fontSize: 14,
            scrollBeyondLastLine: false,
            padding: { top: 16, bottom: 16 }
        });

        // Set initial value for hidden input
        document.getElementById('code-input').value = initialCode;

        // Language switching
        document.getElementById('language').addEventListener('change', function() {
            var newLang = this.value;
            if (newLang === 'bash') newLang = 'shell'; // Map bash to shell
            monaco.editor.setModelLanguage(editor.getModel(), newLang);
        });

        // Format Document
        document.getElementById('format-btn').addEventListener('click', function() {
            editor.getAction('editor.action.formatDocument').run();
        });

        // Sync with hidden input on form submit
        document.getElementById('snippet-form').addEventListener('submit', function() {
            document.getElementById('code-input').value = editor.getValue();
        });
        
        // Also sync on content change just in case
        editor.onDidChangeModelContent(function() {
            document.getElementById('code-input').value = editor.getValue();
        });
    });
</script>
@endpush
@endsection
