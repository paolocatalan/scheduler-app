@extends('layouts.app')
@section('content')
  <main>
    <h1>Login</h1>
    <form method="post" action="/login">
      @csrf
      <label for="email">Email</label><br>
      <input type="email" id="email" name="email" :value="old('email')" required><br>
      @error('email')
        <small style="color:#d32f2f;">{{ $message }}</small>
      @enderror
      <br>
      <label for="password">Password</label><br>
      <input type="password" id="password" name="password" required><br>
      @error('password')
        <small style="color:#d32f2f;">{{ $message }}</small>
      @enderror
      <br>
      <input type="submit" value="Login">
    </form>
  </main>
@endsection
