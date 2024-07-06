@extends('layouts.app')
@section('content')
  <main>
    <p>Hire me as a Web Developer Expert</p>
    <ul>
      <li>I excel in diagnosing issues and provide effective solutions.</li>
      <li>I automate everything possible.</li>
      <li>I use the best tools and packages to achieve your website goals.</li>
      <li>I stay updated on the latest best practices to keep projects on track.</li>
      <li>My code optimization skills make your applications run faster and smoother.</li>
      <li>I am curious and knowledgeable about the latest tech trends and culture.</li>
      <li>I have strong communication skills to ensure clear and effective collaboration.</li>
    </ul>
    <p>Let's talk about your project, your goals and how I can help.</p>
    <a href="/schedule-a-call/?date={{ date('Y-m-d') }}">Book a call</a>
  </main>
@endsection
