@extends('layouts.app')
@section('content')
<main class="site-main">
  <h1>Hire me as a Web Developer expert</h1>
  <p>I build business and application logic in PHP.</p>
  <ul>
    <li>I excel in problem solving and provide effective solutions.</li>
    <li>I automate everything possible.</li>
    <li>I use the best tools and packages to achieve your website goals.</li>
    <li>Updated on the latest best practices to keep projects on track.</li>
    <li>My code optimization skills make your applications run faster and smoother.</li>
    <li>Curious and knowledgeable about the latest tech trends and culture.</li>
    <li>Strong communication skills to ensure clear and effective collaboration.</li>
  </ul>
  <p>Let's talk about your project, your goals and how I can help.</p>
  <button class="button" hx-get="/schedule-a-call/?date={{ date('Y-m-d') }}" hx-target="body" hx-push-url="true">Schedule a Call</button>
</main>
@endsection
