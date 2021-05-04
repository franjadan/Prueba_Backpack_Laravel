<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function getPosts(){

        $posts = Post::paginate(10);

        return $posts;
    }

    public function getPost($id){

        $post = Post::find($id);
        return $post;
    }
}
