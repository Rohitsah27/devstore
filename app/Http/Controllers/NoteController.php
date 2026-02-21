<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $documents = $this->firebase->getAllDocuments('notes');
        
        $notes = [];
        foreach($documents as $doc) {
            $normalized = $this->firebase->normalizeDocument($doc);
            if ($normalized) {
                $notes[] = $normalized;
            }
        }

        // Sort by pinned then by date (client-side sort for now since firestore ordering requires indexes)
        usort($notes, function($a, $b) {
            // Pinned first
            if (($a['is_pinned'] ?? false) && !($b['is_pinned'] ?? false)) return -1;
            if (!($a['is_pinned'] ?? false) && ($b['is_pinned'] ?? false)) return 1;
            
            // Then by date desc
            $dateA = $a['created_at'] ?? null;
            $dateB = $b['created_at'] ?? null;
            
            $tsA = $dateA instanceof \DateTime ? $dateA->getTimestamp() : 0;
            $tsB = $dateB instanceof \DateTime ? $dateB->getTimestamp() : 0;

            return $tsB - $tsA;
        });

        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'is_pinned' => 'nullable|boolean',
        ]);

        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];
        
        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'tags' => $tags,
            'is_pinned' => $request->has('is_pinned'),
            'created_at' => new \DateTime(),
        ];

        $this->firebase->createDocument('notes', $data);

        return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }

    public function edit($id)
    {
        $doc = $this->firebase->getDocument('notes', $id);
        $note = $this->firebase->normalizeDocument($doc);
        
        if(!$note) {
            return redirect()->route('notes.index')->with('error', 'Note not found.');
        }

        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'nullable|string',
            'is_pinned' => 'nullable|boolean',
        ]);

        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];
        
        $data = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'tags' => $tags,
            'is_pinned' => $request->has('is_pinned'),
            'updated_at' => new \DateTime(),
        ];

        $this->firebase->updateDocument('notes', $id, $data);

        return redirect()->route('notes.index')->with('success', 'Note updated successfully.');
    }

    public function destroy($id)
    {
        $this->firebase->deleteDocument('notes', $id);
        return redirect()->route('notes.index')->with('success', 'Note deleted successfully.');
    }
}
