<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->get("userId");
        $posts = Post::with("user")
            ->when($userId, function (Builder $builder) use ($userId) {
                $builder->where("user_id", "=", $userId);
            })->latest()->get();
        return response()->json($posts);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $user = auth()->user();
        $post = $user->posts()->create($data);
        return response()->json($post->fresh("user"), 201);
    }
    public function show(Post $post)
    {
        return response()->json($post);
    }
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $user = auth()->user();
        $post = $user->posts()->findOrFail($post->id);
        $post->update($data);
        return response()->json($post->fresh("user"));
    }
    public function destroy(Post $post)
    {
        $user = auth()->user();
        $post = $user->posts()->findOrFail($post->id);
        $post->delete();
        return response()->json();
    }
}