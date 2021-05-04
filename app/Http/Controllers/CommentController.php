<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function getComments($id){

        $comments = Comment::where('post_id', $id)->paginate(3);

        return response()->json($comments);
    }

    public function saveComment($id, Request $request){

        $comment = new Comment; 

        $comment->forceFill([
            'post_id' => $id,
            'comment' => $request->get('comment')
        ]);

        $comment->save();

    }
}
