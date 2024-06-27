@extends('layouts.app')
@section('content')
  <main>
    <h1>Register</h1>
    <form method="post" action="/register">
      @csrf
      <label for="email">Email</label><br>
      <input type="email" id="email" name="email" required><br>
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
      <label for="password_confirmation">Confirm Password</label><br>
      <input type="password" id="password_confirmation" name="password_confirmation" required><br>
      @error('password_confirmation')
        <small style="color:#d32f2f;">{{ $message }}</small>
      @enderror
      <br>
      <input type="submit" value="Register">
    </form>
  </main>
@endsection
