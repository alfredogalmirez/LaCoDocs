<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TopicController extends Controller
{
    public function index(){
        $topics = Auth::user()->topics()
            ->with('parent')
            ->orderBy('name')
            ->get();
            
        return Inertia::render('Topic/Index', [
            'topics' => $topics,
        ]);
    }    
    
    public function store(Request $request){
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:topics,name',
                Rule::unique('topics')->where('user_id', Auth::id()),
            ],
            'parent_id' => ['nullable', 'integer', 'exists:topics,id',
                Rule::exists('topics', 'id')->where('user_id', Auth::id()),
            ],
        ]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $user->topics()->create([   
           'name' => $validated['name'],
           'slug' =>  Str::slug($validated['name']),
           'parent_id' => $validated['parent_id'] ?? null,
        ]);
        
        return redirect()->route('topics.index')
            ->with('success', 'Topic created successfully.');
    }

    public function show(Topic $topic){
        if($topic->user_id !== Auth::id()) {
            abort(403);
        }    
    
        $topic->load([
            'children.documents',
            'documents',
        ]);

        return Inertia::render('Topic/Show', [
            'topic' => $topic,
        ]);
    }
    
    public function edit(Topic $topic){
        if ($topic->user_id !== Auth::id()) {
            abort(403);
        }    
    
        return Inertia::render('Topic/Edit', [
           'topic' => $topic,
           'parentTopics' => Topic::where('id', '!=', $topic->id)
                ->get(['id', 'name'])
        ]);
    }
    
    public function update(Request $request, Topic $topic){
        if ($topic->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validated = $request->validate([
           'name' => ['required', 'string', 'max:255',
                Rule::unique('topics')->where('user_id', Auth::id())->ignore($topic->id),
           ],
           'parent_id' => ['nullable',
                Rule::exists('topics', 'id')->where('user_id', Auth::id()),
           ] 
        ]);
        
        if($validated['parent_id'] == $topic->id) {
            return back()->withErrors(['parent_id' => 'A topic cannot be its own parent.']);
        }
        
        $topic->update([
           'name' => $validated['name'],
           'slug' => Str::slug($validated['name']),
           'parent_id' => $validated['parent_id'],
        ]); 
        
        return redirect()->route('topics.index')
        ->with('success', 'Topic updated successfully.');
    }
    
    public function destroy(Topic $topic){
        if ($topic->user_id !== Auth::id()) {
            abort(403);
        }    
    
        $topic->delete();
        
        return redirect()->route('docs.index')
            ->with('succes', 'Topic deleted successfuly.');
    }
}
