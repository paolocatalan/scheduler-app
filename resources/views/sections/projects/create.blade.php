@extends('layouts.app')
@section('content')
<main class="site-main">
  <h1>Create Project</h1>
  <form method="post" enctype="multipart/form-data" action="{{ route('projects.store') }}">
    @csrf
    <label for="title">Title</label><br>
    <input type="text" id="title" name="title" required><br>
    @error('title')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="slug">slug</label><br>
    <input type="text" id="slug" name="slug" required><br>
    @error('slug')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="excerpt">Excerpt</label><br>
    <textarea id="excerpt" name="excerpt" required></textarea><br>
    @error('excerpt')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="body">Body</label><br>
    <textarea id="body" name="body" rows="10" cols="50" required></textarea><br>
    @error('body')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="thumbnail">Thumbnail</label><br>
    <input type="file" name="thumbnail" required><br>
    @error('thumbnail')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <br>
    <a href="/projects/">Cancel</a>
    <input type="submit" value="Submit">
  </form>
</main>
@endsection
