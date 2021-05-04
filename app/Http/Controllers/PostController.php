<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function getPosts(){

        $posts = Post::paginate(5);

        return response()->json($posts);
    }

    public function getPost($id){

        $post = Post::find($id);
        return $post;
    }
}
