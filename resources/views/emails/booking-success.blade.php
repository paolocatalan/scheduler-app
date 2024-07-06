<p>Hi {{ $name; }},</p>
<p>I can confirm that I have received your meeting request. The date and time are great, and I've added it to my diary.</p>

<p>Date: {{ date('l, F j, Y, g:i a', strtotime($date)) }}</p>
<p>URL: {{ $meetLink }}</p>

<p>If you have any questions or need to change your appointment, reply to this email</p>
<p>Cheers</p>
<p>Paolo Catalan</p>