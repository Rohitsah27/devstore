<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        // getAllDocuments now returns an array of normalized documents
        $resources = $this->firebase->getAllDocuments('resources');
        
        // Sort by date desc
        usort($resources, function($a, $b) {
            $tsA = isset($a['created_at']) && $a['created_at'] instanceof \DateTime ? $a['created_at']->getTimestamp() : 0;
            $tsB = isset($b['created_at']) && $b['created_at'] instanceof \DateTime ? $b['created_at']->getTimestamp() : 0;
            return $tsB - $tsA;
        });

        return view('resources.index', compact('resources'));
    }

    public function create()
    {
        return view('resources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        $file = $request->file('file');
        
        // Store in public disk
        $path = $file->store('resources', 'public');
        $url = asset('storage/' . $path);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_url' => $url,
            'file_path' => $path, // Store path for deletion
            'file_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'created_at' => (new \DateTime())->format(\DateTime::ISO8601),
        ];

        $this->firebase->createDocument('resources', $data);

        return redirect()->route('resources.index')->with('success', 'Resource uploaded successfully.');
    }

    public function destroy($id)
    {
        $doc = $this->firebase->getDocument('resources', $id);
        
        if($doc) {
            // Delete from storage if path exists
            if(isset($doc['file_path'])) {
                try {
                    Storage::disk('public')->delete($doc['file_path']);
                } catch (\Exception $e) {
                    // Log error but continue
                }
            }
            
            $this->firebase->deleteDocument('resources', $id);
            return redirect()->route('resources.index')->with('success', 'Resource deleted successfully.');
        }
        
        return redirect()->route('resources.index')->with('error', 'Resource not found.');
    }
}
