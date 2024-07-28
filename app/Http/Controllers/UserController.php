<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->get("page");
        $search = $request->get("search");
        $withPosts = $request->get("withPosts");

        $usersQuery = User::with("posts")
            ->when($withPosts === "true", function (Builder $builder) {
                $builder->has("posts");
            })
            ->when($withPosts === "false", function (Builder $builder) {
                $builder->doesntHave("posts");
            })
            ->search($search)->latest();

        if ($page) {
            $users = $usersQuery->paginate(4);
        } else {
            $users = $usersQuery->get();
        }
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:4',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4',
        ]);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|min:4',
            'image' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $path = $request->file('image')->store('users/avatars', 'public');
            $data['image'] = $path;
        }
        $user->update($data);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}