<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FirebaseService
{
    protected $databaseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->databaseUrl = rtrim(env('FIREBASE_DATABASE_URL'), '/');
        $this->apiKey = env('FIREBASE_API_KEY');
    }

    /**
     * Helper to build the URL for a collection/path
     */
    protected function getUrl($path)
    {
        return "{$this->databaseUrl}/{$path}.json";
    }

    /**
     * Create a new document (push to a list)
     */
    public function createDocument($collectionName, $data)
    {
        // Add created_at timestamp if not present
        if (!isset($data['created_at'])) {
            $data['created_at'] = (new \DateTime())->format(\DateTime::ISO8601);
        }
        
        $response = Http::post($this->getUrl($collectionName), $data);
        
        if ($response->successful()) {
            return $response->json()['name'] ?? null;
        }
        
        return null;
    }

    /**
     * Update a document
     */
    public function updateDocument($collectionName, $id, $data)
    {
        // Add updated_at timestamp
        $data['updated_at'] = (new \DateTime())->format(\DateTime::ISO8601);
        
        $response = Http::patch($this->getUrl("{$collectionName}/{$id}"), $data);
        return $response->successful();
    }

    /**
     * Delete a document
     */
    public function deleteDocument($collectionName, $id)
    {
        $response = Http::delete($this->getUrl("{$collectionName}/{$id}"));
        return $response->successful();
    }
    
    /**
     * Get a single document
     */
    public function getDocument($collectionName, $id)
    {
        $response = Http::get($this->getUrl("{$collectionName}/{$id}"));
        
        if ($response->successful() && $response->json() !== null) {
            return $this->normalizeDocument($response->json(), $id);
        }
        
        return null;
    }

    /**
     * Get all documents from a collection (list)
     */
    public function getAllDocuments($collectionName)
    {
        $response = Http::get($this->getUrl($collectionName));
        
        if (!$response->successful() || $response->json() === null) {
            return [];
        }

        $documents = $response->json();
        
        // Convert associative array to list of normalized documents
        $normalizedDocs = [];
        if (is_array($documents)) {
            foreach ($documents as $key => $data) {
                if (is_array($data)) {
                    $normalizedDocs[] = $this->normalizeDocument($data, $key);
                }
            }
        }
        
        return $normalizedDocs;
    }

    /**
     * Get recent documents from a collection (ordered by created_at desc)
     */
    public function getRecentDocuments($collectionName, $limit = 5)
    {
        // For REST API, ordering requires rules indexing or client-side sorting.
        // We'll use limitToLast query param assuming default key ordering is roughly chronological
        // or rely on client-side sorting if dataset is small.
        // To properly order by child 'created_at', we need: orderBy="created_at"&limitToLast=5
        
        $url = $this->getUrl($collectionName) . '?orderBy="created_at"&limitToLast=' . $limit;
        
        $response = Http::get($url);
        
        if (!$response->successful() || $response->json() === null) {
            // Fallback: fetch all and slice (safe for small personal datasets)
            $all = $this->getAllDocuments($collectionName);
            // Sort by created_at desc
            usort($all, function($a, $b) {
                $tA = isset($a['created_at']) && $a['created_at'] instanceof \DateTime ? $a['created_at']->getTimestamp() : 0;
                $tB = isset($b['created_at']) && $b['created_at'] instanceof \DateTime ? $b['created_at']->getTimestamp() : 0;
                return $tB - $tA;
            });
            return array_slice($all, 0, $limit);
        }

        $documents = $response->json();
        $normalizedDocs = [];
        
        if (is_array($documents)) {
            foreach ($documents as $key => $data) {
                if (is_array($data)) {
                    $normalizedDocs[] = $this->normalizeDocument($data, $key);
                }
            }
        }
        
        // Results from orderBy come back sorted ascending, reverse for recent first
        return array_reverse($normalizedDocs);
    }

    /**
     * Normalize a document array with ID and DateTime objects
     */
    public function normalizeDocument($data, $id = null)
    {
        if (!$data) {
            return null;
        }

        // If data is just the raw array from getAllDocuments loop, it might not have ID yet
        if ($id) {
            $data['id'] = $id;
        }

        // Handle timestamps
        foreach (['created_at', 'updated_at'] as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                try {
                    $data[$field] = new \DateTime($data[$field]);
                } catch (\Exception $e) {
                    // Keep as string if parsing fails
                }
            }
        }

        return $data;
    }
    
    // Compatibility methods for controllers expecting getDatabase() or getReference()
    // We'll remove these calls from controllers or stub them here if needed, 
    // but better to stick to the high-level API defined above.
    
    // Storage getter for ResourceController - Not needed as we switched to local storage
    public function getStorage()
    {
        return null; 
    }
}
