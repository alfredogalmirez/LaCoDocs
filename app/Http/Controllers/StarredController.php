<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StarredController extends Controller
{
    public function index(){
        $documents = Auth::user()->documents()
            ->where('is_starred', true)
            ->with('topic')
            ->latest()
            ->get(['id', 'title', 'slug', 'topic_id', 'updated_at', 'is_starred']);
            
        return Inertia::render('Starred/Index', [
           'documents'  => $documents 
        ]);
    }   
    
    public function toggle(Document $document){
        if ($document->user_id !== Auth::id()){
            abort(403);
        }
        
        $isStarred = !$document->is_starred;
        
        $document->update([
            'is_starred' => $isStarred
        ]);
        
        $message = $isStarred 
            ? 'Document added to Favorites.'
            : 'Document removed from starred';
        
        return back()->with('success', $message);
    }
}
