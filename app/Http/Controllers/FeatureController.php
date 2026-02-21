<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class FeatureController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        $documents = $this->firebase->getAllDocuments('features');
        
        $features = [];
        foreach($documents as $doc) {
            $normalized = $this->firebase->normalizeDocument($doc);
            if ($normalized) {
                $features[] = $normalized;
            }
        }
        
        // Custom sort: Priority (High > Medium > Low) then Status (In Progress > New > On Hold > Completed)
        $priorityOrder = ['High' => 3, 'Medium' => 2, 'Low' => 1];
        $statusOrder = ['In Progress' => 4, 'New' => 3, 'On Hold' => 2, 'Completed' => 1];

        usort($features, function($a, $b) use ($priorityOrder, $statusOrder) {
            $pA = $priorityOrder[$a['priority'] ?? 'Low'] ?? 0;
            $pB = $priorityOrder[$b['priority'] ?? 'Low'] ?? 0;
            
            if ($pA !== $pB) return $pB - $pA; // Higher priority first

            $sA = $statusOrder[$a['status'] ?? 'New'] ?? 0;
            $sB = $statusOrder[$b['status'] ?? 'New'] ?? 0;

            return $sB - $sA; // Higher status value first
        });

        return view('features.index', compact('features'));
    }

    public function create()
    {
        return view('features.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:New,In Progress,Completed,On Hold',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'created_at' => new \DateTime(),
        ];

        $this->firebase->createDocument('features', $data);

        return redirect()->route('features.index')->with('success', 'Feature idea added successfully.');
    }

    public function edit($id)
    {
        $doc = $this->firebase->getDocument('features', $id);
        $feature = $this->firebase->normalizeDocument($doc);
        
        if(!$feature) return redirect()->route('features.index')->with('error', 'Feature not found.');
        
        return view('features.edit', compact('feature'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:New,In Progress,Completed,On Hold',
            'priority' => 'required|in:Low,Medium,High',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'updated_at' => new \DateTime(),
        ];

        $this->firebase->updateDocument('features', $id, $data);

        return redirect()->route('features.index')->with('success', 'Feature updated successfully.');
    }

    public function destroy($id)
    {
        $this->firebase->deleteDocument('features', $id);
        return redirect()->route('features.index')->with('success', 'Feature deleted successfully.');
    }
}
