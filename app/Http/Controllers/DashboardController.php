<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        return Inertia::render('Dashboard', [
            'stats' => [
                'documents' => Auth::user()->documents()->count(),
                'topics'    => Auth::user()->topics()->count(),
            ], 
            'recentDocuments' => Auth::user()->documents()->with('topic')
                ->latest('updated_at')
                ->take(5)
                ->get(['id', 'title', 'slug', 'topic_id', 'updated_at']),
            'topics' => Auth::user()->topics()->whereNull('parent_id')
                ->withCount('documents')
                ->take(6)
                ->get(['id', 'name', 'slug']),
        ]);
    }
}
