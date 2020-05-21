@extends('layouts.app')

  @section('extra-js')
  <script type="text/javascript">
    function toggleReplyComment(id){
      let element = document.getElementById('replyComment-' + id);
      element.classList.toggle('d-none');
    }
  </script>
  @endsection

@section('content')
<style media="screen">
  .hrcolor {
    background: blue;
    height: 2px;
    margin-top: 30px;
    margin-bottom: 30px;
  }
</style>

  <div class="container">
    <!--*********************** DETAILS DU TOPIC ***********************-->
    <div class="card">
      <div class="card-body">
        <h5>{{ $topic->title }}</h5>
        <hr>
        <p>{{ $topic->content }}</p>
        <div class="d-flex justify-content-between">
          <small>Posté le {{ $topic->created_at->format('d/m/y à H:m') }}</small>
          <span class="badge badge-primary">{{ $topic->user->name }}</span>
        </div>
      </div>
      <!--*********************** EDITER LE TOPIC ***********************-->
      <div class="d-flex justify-content-between mt-3">
        @can('update', $topic)
        <a href="{{ route('topics.edit', $topic) }}" class="btn btn-warning">Editer ce topic</a>
        @endcan
          @can('delete', $topic)
          <!--*********************** SUPPRIMER LE TOPIC ***********************-->
        <form class="" action="{{ route('topics.destroy', $topic) }}" method="post">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
        @endcan
      </div>
    </div>

    <hr class="hrcolor">

    <h5>Commentaires</h5>
      @forelse($topic->comments as $comment)
      <div class="card mt-2">
        <div class="card-body d-flex justify-content-between">
          <div class="">
            {{ $comment->content }}
              <div class="d-flex justify-content-between align-items-center">
                <small>Posté le {{ $comment->created_at->format('d/m/y') }}, commenté par : &emsp; </small>
                <span class="badge badge-primary">{{ $comment->user->name }}</span>
              </div>
          </div>
          <div class="">
            @if(!$topic->solution && auth()->user()->id == $topic->user_id)
              <solution-button topic-id="{{ $topic->id }}" comment-id="{{ $comment->id}}"></solution-button>
            @else
              @if($topic->solution == $comment->id)
              <h4><span class="badge badge-success">Marqué comme Solution</span></h4>
              @endif
            @endif
          </div>
        </div>
      </div>
      <!--  FIN DETAILS DU TOPIC  -->

      @foreach($comment->comments as $replyComment)
      <div class="card mt-2 ml-5">
        <div class="card-body">
          {{ $replyComment->content }}
          <div class="d-flex justify-content-between align-items-center">
            <small>Posté le {{ $replyComment->created_at->format('d/m/y') }}</small>
            <span class="badge badge-primary">{{ $replyComment->user->name }}</span>
          </div>
        </div>
      </div>
      @endforeach

      <!--*********************** FORMULAIRE DE REPONSE AU COMMENTAIRE ***********************-->
      @auth
      <button type="button"  name="button" class="btn btn-info mt-3" onclick="toggleReplyComment( {{ $comment->id }} )">Répondre</button>
      <form action="{{ route('comments.storeReply', $comment) }}" method="POST" class="mb-3 ml-5 d-none" id="replyComment-{{ $comment->id }}" >
        @csrf
        <div class="form-group">
          <label for="replyComment">Ma réponse</label>
          <textarea name="replyComment" class="form-control @error('replyComment') is-invalid @enderror" id="replyComment" rows="5" cols="80"></textarea>
          @error('content')
          <div class="invalid-feedback">{{ $errors->first('replyComment') }}</div>
          @enderror
        </div>
        <button type="submit" class="btn btn-primary">Répondre à ce commentaire</button>
      </form>
      @endauth

      <!--*********************** FORMULAIRE D'AJOUT DE COMMENTAIRE ***********************-->
      @empty
      <div class="alert alert-info">Aucun commentaire pour ce topic</div>
      @endforelse

    <form action="{{ route('comments.store', $topic) }}" class="mt-3" method="post">
      @csrf
      <div class="form-group">
        <label for="content">Votre commentaire</label>
        <textarea class="form-control @error('content') is-invalid @enderror" name="content" id="content" rows="4" cols="80"></textarea>
        @error('content')
        <div class="invalid-feedback">{{ $errors->first('content') }}</div>
        @enderror
      </div>
      <button type="submit" class="btn btn-primary">Soumettre mon commentaire</button>
    </form>
  </div>

@endsection
