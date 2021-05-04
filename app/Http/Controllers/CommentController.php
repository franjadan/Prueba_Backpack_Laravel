<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function getComments($id){

        $comments = Comment::where('post_id', $id)->paginate(10);

        return $comments;
    }
}
