<?php

namespace App\Http\Controllers;

use App\Mail\PostLiked;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PostLikeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function store(Request $request, Post $post)
    {

        $user = $request->user();

        if ($post->likedBy($user)) {
            return response(null, 409);
        }

        $post->likes()->create([
            'user_id' => $user->id
        ]);

        Mail::to($post->user)->send(new PostLiked($user, $post));

        return back();
    }

    public function destroy(Request $request, Post $post)
    {

        $request->user()->likes()->where('post_id', $post->id)->delete();

        return back();
    }
}
