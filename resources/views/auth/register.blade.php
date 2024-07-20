@extends('layouts.app')
@section('content')
<main class="site-main">
  <h1>Register</h1>
  <form method="post" action="/register" hx-post="/register" hx-target="body">
    @csrf
    <label for="name">Name</label><br>
    <input type="name" id="name" name="name" value="{{ old('name') }}"><br>
    @error('name')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="email">Email</label><br>
    <input type="email" id="email" name="email" value="{{ old('email') }}"><br>
    @error('email')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="password">Password</label><br>
    <input type="password" id="password" name="password"><br>
    @error('password')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <label for="password_confirmation">Confirm Password</label><br>
    <input type="password" id="password_confirmation" name="password_confirmation"><br>
    @error('password_confirmation')
      <small style="color:#d32f2f;">{{ $message }}</small>
    @enderror
    <br>
    <input type="submit" value="Register">
  </form>
</main>
@endsection
