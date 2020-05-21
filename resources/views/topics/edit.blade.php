@extends('layouts.app')

@section('content')
  <div class="container">
    <h1>{{ $topic->title }}</h1>
    <hr>
    <form class="" action="{{ route('topics.update', $topic) }}" method="post">
      @csrf
      <!-- cette methode est utilisée pour updater les informations. en cas de doute faire php arisan route:list dans invite de cmd -->
      @method('PATCH')
      <div class="form-group">
        <label for="title">Titre du Topic</label>
        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="title" value="{{ $topic->title }}">
      </div>
      @error('title')
      <div class="invalid-feedback">
        {{ $errors->first('title') }}
      </div>
      @enderror
      <div class="form-group">
        <label for="content">Votre sujet</label>
        <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="8" cols="80" id="content">{{ $topic->content }}</textarea>
      </div>
      @error('content')
      <div class="invalid-feedback">
        {{ $errors->first('content') }}
      </div>
      @enderror
      <button type="submit" class="btn btn-primary" name="button">Modifier mon topic</button>
    </form>
  </div>

@endsection
