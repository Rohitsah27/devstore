<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class SnippetController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $documents = $this->firebase->getAllDocuments('snippets');
        
        $snippets = [];
        foreach($documents as $doc) {
            $normalized = $this->firebase->normalizeDocument($doc);
            if ($normalized) {
                $snippets[] = $normalized;
            }
        }
        
        // Sort by language then title
        usort($snippets, function($a, $b) {
            $langCmp = strcmp($a['language'] ?? '', $b['language'] ?? '');
            if ($langCmp !== 0) return $langCmp;
            return strcmp($a['title'] ?? '', $b['title'] ?? '');
        });

        return view('snippets.index', compact('snippets'));
    }

    public function create()
    {
        return view('snippets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string',
            'language' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $data = [
            'title' => $validated['title'],
            'code' => $validated['code'],
            'language' => $validated['language'],
            'description' => $validated['description'],
            'created_at' => new \DateTime(),
        ];

        $this->firebase->createDocument('snippets', $data);

        return redirect()->route('snippets.index')->with('success', 'Snippet added successfully.');
    }

    public function edit($id)
    {
        $doc = $this->firebase->getDocument('snippets', $id);
        $snippet = $this->firebase->normalizeDocument($doc);
        
        if(!$snippet) return redirect()->route('snippets.index')->with('error', 'Snippet not found.');
        
        return view('snippets.edit', compact('snippet'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string',
            'language' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $data = [
            'title' => $validated['title'],
            'code' => $validated['code'],
            'language' => $validated['language'],
            'description' => $validated['description'],
            'updated_at' => new \DateTime(),
        ];

        $this->firebase->updateDocument('snippets', $id, $data);

        return redirect()->route('snippets.index')->with('success', 'Snippet updated successfully.');
    }

    public function destroy($id)
    {
        $this->firebase->deleteDocument('snippets', $id);
        return redirect()->route('snippets.index')->with('success', 'Snippet deleted successfully.');
    }
}
