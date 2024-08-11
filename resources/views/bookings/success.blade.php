@extends('layouts.app')
@section('content')
<main class="site-main">
<pre>
╔══════════════════════════════════════════════════════════════════╗
║                            Confirmed                             ║
║               You are scheduled with Paolo Catalan               ║
╚══════════════════════════════════════════════════════════════════╝
</pre>
  <h4>Introduction and diagnosis meeting</h4>
  <p>Date: {{ $date->format('g:i a, l, F j, Y') }}</p>
  <p>Timezone: {{ str_replace('_', ' ' , $timezone) }}</p>
  <p>Web conferencing details to follow.</p>
  <em><strong>A confirmation email has been sent to your email address.</strong></em>
</main>
@endsection
