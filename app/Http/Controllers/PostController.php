<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $post = Post::create($data);
        return response()->json($post, 201);
    }
    public function show(Post $post)
    {
        return response()->json($post);
    }
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);
        $post->update($data);
        return response()->json($post);
    }
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(null, 204);
    }
}