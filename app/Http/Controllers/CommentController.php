<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;
use App\Comment;
use App\Notifications\NewCommentPosted;

class CommentController extends Controller
{
    public function __contruct(){
      $this->middleware('auth');
    }

    public function store(Topic $topic){
      request()->validate([
        'content' => 'required|min:5'
      ]);
      $comment = new Comment();
      $comment->content = request('content');
        //A la création des données, il faut être un user authentifié
      $comment->user_id = auth()->user()->id;

      $topic->comments()->save($comment);

      //Notification
      $topic->user->notify(new NewCommentPosted($topic, auth()->user()));

      //rediection vers le topic que l'on vient de commenter
      return redirect()->route('topics.show', $topic);
    }


    public function storeCommentReply(Comment $comment){

      request()->validate([
        'replyComment' => 'required|min:3'
      ]);

      $commentReply = new Comment();
      $commentReply->content = request('replyComment');
      $commentReply->user_id = auth()->user()->id;

      $comment->comments()->save($commentReply);

      //rediection vers la page précédente
      return redirect()->back();
    }

    public function markedAsSolution(Topic $topic, Comment $comment){
      if (auth()->user()->id == $topic->user_id) {

        $topic->solution = $comment->id;
        $topic->save();
        return response()->json(['success' => ['success' => 'Marqué comme solution']], 200);

      } else {
        return response()->json(['errors' => ['errors' => 'utilisateur non valide']], 401);
      }
    }
}
