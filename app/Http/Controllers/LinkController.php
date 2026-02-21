<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class LinkController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $documents = $this->firebase->getAllDocuments('links');
        
        $links = [];
        foreach($documents as $doc) {
            $normalized = $this->firebase->normalizeDocument($doc);
            if ($normalized) {
                $links[] = $normalized;
            }
        }
        
        // Sort by category then title
        usort($links, function($a, $b) {
            $catCmp = strcmp($a['category'] ?? '', $b['category'] ?? '');
            if ($catCmp !== 0) return $catCmp;
            return strcmp($a['title'] ?? '', $b['title'] ?? '');
        });

        return view('links.index', compact('links'));
    }

    public function create()
    {
        return view('links.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $data = [
            'title' => $validated['title'],
            'url' => $validated['url'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'created_at' => new \DateTime(),
        ];

        $this->firebase->createDocument('links', $data);

        return redirect()->route('links.index')->with('success', 'Link added successfully.');
    }

    public function edit($id)
    {
        $doc = $this->firebase->getDocument('links', $id);
        $link = $this->firebase->normalizeDocument($doc);
        
        if(!$link) return redirect()->route('links.index')->with('error', 'Link not found.');
        
        return view('links.edit', compact('link'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $data = [
            'title' => $validated['title'],
            'url' => $validated['url'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'updated_at' => new \DateTime(),
        ];

        $this->firebase->updateDocument('links', $id, $data);

        return redirect()->route('links.index')->with('success', 'Link updated successfully.');
    }

    public function destroy($id)
    {
        $this->firebase->deleteDocument('links', $id);
        return redirect()->route('links.index')->with('success', 'Link deleted successfully.');
    }
}
