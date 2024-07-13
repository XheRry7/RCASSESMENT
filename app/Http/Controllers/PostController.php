<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ContentFilterService;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(Request $request, ContentFilterService $contentFilter)
    {
        $request->validate(['content' => 'required|string']);

        if (!$contentFilter->filter($request->content)) {
            return response()->json(['message' => 'Content contains prohibited words'], 400);
        }

        return Post::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);
    }

    public function show(Post $post)
    {
        return $post;
    }

    public function update(Request $request, Post $post, ContentFilterService $contentFilter)
    {
        $this->authorize('update', $post);
        $request->validate(['content' => 'required|string']);

        if (!$contentFilter->filter($request->content)) {
            return response()->json(['message' => 'Content contains prohibited words'], 400);
        }

        $post->update(['content' => $request->content]);
        return $post;
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return response()->noContent();
    }
}

