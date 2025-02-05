@extends('layouts.app')
@section('content')
<main class="site-main">
  <h1>Edit Project: {{ $project->title }}</h1>
  <form method="post" enctype="multipart/form-data" action="{{ route('projects.update', ['project' => $project]) }}">
    @csrf
    @method('PATCH')
    <label for="title">Title</label><br>
    <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}" required><br>
    @error('title')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="slug">Slug</label><br>
    <input type="text" id="slug" name="slug" value="{{ old('slug', $project->slug) }}" required><br>
    @error('slug')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="excerpt">Excerpt</label><br>
    <textarea id="excerpt" name="excerpt" required>{!! old('excerpt', $project->excerpt) !!}</textarea><br>
    @error('excerpt')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="body">Body</label><br>
    <textarea id="body" name="body" rows="10" cols="50" required>{!! old('body', $project->body) !!}</textarea><br>
    @error('body')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    @if ($project->thumbnail)
      <img src="{{ asset('storage/' . $project->thumbnail) }}" width="225"><br>
    @endif
    <label for="thumbnail">Thumbnail</label><br>
    <input type="file" name="thumbnail" value="old('thumbnail'), $post->thumbnail"><br>
    @error('thumbnail')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <br>
    @can('delete', $project)
      <button form="delete-form" type="submit">Delete</button>
    @endcan
    <a href="/projects/{{ $project->slug }}">Cancel</a>
    <input type="submit" value="Update Project">
  </form>
  <form method="post" action="{{ route('projects.destroy', ['project' => $project]) }}" id="delete-form">
    @csrf
    @method('DELETE')
  </form>
</main>
@endsection
