<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function index()
    {   
        $topics = Auth::user()->topics()->whereNull('parent_id')
            ->with(['children.documents', 'documents'])
            ->get();

        return Inertia::render('Document/Index', [
            'topics' => $topics
        ]);
    }

    public function show(Document $document)
    {

        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $document->load('topic');

        return Inertia::render('Document/Show', [
            'document' => $document,
        ]);
    }

    public function create(Request $request)
    {
        return Inertia::render('Document/Create', [
            'topics' => Auth::user()->topics()->orderBy('name')->get(['id', 'name']),
            'selectedTopicId' => $request->query('topic_id'),
        ]);
    }

    public function edit(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Document/Edit', [
            'document' => $document,
            'topics' => Auth::user()->topics()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'topic_id' => ['required', Rule::exists('topics', 'id')->where('user_id', Auth::id())],
            'content_blocks.blocks' => ['required', 'array', 'min:1'],
            'content_blocks.blocks.*.type' => ['required', 'in:text,code,sql,steps'],
            'content_blocks.blocks.*.content' => ['required ', 'string'],
            'content_blocks.blocks.*.language' => ['nullable', 'string', 'max:50'],
        ]);

        $document = Auth::user()->documents()->create([
            'title' => $validated['title'],
            'topic_id' => $validated['topic_id'],
            'slug' => Str::slug($validated['title']),
            'content_blocks' => $validated['content_blocks']
        ]);

        return redirect()->route('docs.show', $document);
    }

    public function update(Request $request, Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'topic_id' => ['required', Rule::exists('topics', 'id')->where('user_id', Auth::id()),],
            'content_blocks' => ['required', 'array'],
            'content_blocks.blocks' => ['required', 'array', 'min:1'],
            'content_blocks.blocks.*.type' => ['required', 'in:text,code,sql,steps'],
            'content_blocks.blocks.*.content' => ['required', 'string'],
            'content_blocks.blocks.*.language' => ['nullable', 'string', 'max:50'],
        ]);

        $document->update([
            'title' => $validated['title'],
            'topic_id' => $validated['topic_id'],
            'content_blocks' => $validated['content_blocks']
        ]);

        return redirect()->route('docs.show', $document);
    }

    public function destroy(Document $document)
    {
        if ($document->user_id !== Auth::id()) {
            abort(403);
        }

        $document->delete();

        return redirect()->route('docs.index')
            ->with('success', 'Document deleted successfuly.');
    }
}
