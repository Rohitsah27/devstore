<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class DashboardController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function index()
    {
        // Get counts
        // Note: getAllDocuments fetches all data. For a personal app this is fine.
        // For larger apps, we should store counters in a separate node (e.g. 'stats/counts').
        $notesCount = count($this->firebase->getAllDocuments('notes'));
        $linksCount = count($this->firebase->getAllDocuments('links'));
        $snippetsCount = count($this->firebase->getAllDocuments('snippets'));
        $featuresCount = count($this->firebase->getAllDocuments('features'));
        $resourcesCount = count($this->firebase->getAllDocuments('resources'));

        // Recent items
        $recentItems = [];
        $collections = [
            'notes' => ['type' => 'Note', 'route' => 'notes.edit'],
            'links' => ['type' => 'Link', 'route' => 'links.edit'],
            'snippets' => ['type' => 'Snippet', 'route' => 'snippets.edit'],
            'features' => ['type' => 'Feature', 'route' => 'features.edit'],
            'resources' => ['type' => 'Resource', 'route' => null]
        ];

        foreach ($collections as $colName => $meta) {
            $docs = $this->firebase->getRecentDocuments($colName, 3);
            foreach ($docs as $doc) {
                $recentItems[] = [
                    'id' => $doc['id'],
                    'title' => $doc['title'] ?? 'Untitled',
                    'type' => $meta['type'],
                    'route' => $meta['route'],
                    'created_at' => $doc['created_at'] ?? null,
                    'raw_date' => isset($doc['created_at']) && $doc['created_at'] instanceof \DateTime ? $doc['created_at']->getTimestamp() : 0
                ];
            }
        }

        // Sort combined list by date desc
        usort($recentItems, function($a, $b) {
            return $b['raw_date'] - $a['raw_date'];
        });

        // Take top 10
        $recentItems = array_slice($recentItems, 0, 10);

        return view('dashboard', compact('notesCount', 'linksCount', 'snippetsCount', 'featuresCount', 'resourcesCount', 'recentItems'));
    }
}
