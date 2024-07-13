<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        if ($this->containsHateSpeech($request->content)) {
            return response()->json(['message' => 'Content contains prohibited language'], 400);
        }

        $post = auth()->user()->posts()->create($request->all());

        return response()->json($post, 201);
    }

    public function update(Request $request, $id)
    {
        $post = auth()->user()->posts()->findOrFail($id);

        $request->validate([
            'content' => 'required|string',
        ]);

        if ($this->containsHateSpeech($request->content)) {
            return response()->json(['message' => 'Content contains prohibited language'], 400);
        }

        $post->update($request->all());

        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = auth()->user()->posts()->findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted'], 200);
    }

    private function containsHateSpeech($content)
    {
        return false;
    }


}
