<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllPostsCollection;
use App\Models\Post;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);
        if ($user === null) { return redirect()->route('home.index'); }

        $posts = Post::where('user_id', $id)->orderBy('created_at', 'desc')->get();

        $follows = auth()->user()->followings->contains($user) ?? false;

        $followersCount = $user->followers->count();
        $followingsCount = $user->followings->count();
//        dd($followersCount, $followingsCount);
        return Inertia::render('User', [
            'user' => $user,
            'postsByUser' => new AllPostsCollection($posts),
            'status' => $follows,
            'followersCount' => $followersCount,
            'followingsCount' => $followingsCount
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([ 'file' => 'required|mimes:jpg,jpeg,png' ]);
        $user = (new FileService)->updateFile(auth()->user(), $request, 'user');
        $user->save();

        return redirect()->route('users.show',['id' => auth()->user()->id]);
    }

    public function follow(User $user)
    {
//        dd($request->input('userId'));
        $follower = auth()->user();
//        $user = User::find($request->input('userId'));
        $follower->followings()->toggle($user);
//        dd('123123');
        return ['123' => 'succes'];
    }
}
