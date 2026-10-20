<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Controller;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function index(Request $request){
        $query = $request->input('q');
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $documents = [];
        $topics = [];
        
        if($query){
            $documents = $user->documents()
                ->where('title', 'like', "%{$query}%")
                ->orWhere('content_blocks', 'like', "%{$query}%")
                ->take(15)
                ->get(['id', 'title', 'slug', 'topic_id', 'updated_at']);
                
            $topics = $user->topics()
                ->where('name', 'like', "%{$query}%")
                ->take(10)
                ->get(['id', 'name', 'slug']);
        }
        
        return Inertia::render('Search/Results', [
            'query' => $query,
            'documents' => $documents,
            'topics'    => $topics,
        ]);
    }
}
