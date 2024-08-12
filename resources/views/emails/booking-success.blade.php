<p>Hi {{ $name }},</p>
<p>Thank you for scheduling a call. Looking forward to meeting you.</p>
<p>Date: {{ date('l, F j, Y, g:i a', strtotime($date)) }}</p>
<p>Timezone: {{ $timezone }}</p>
<p>URL: {{ $meetingLink }}</p>
<p>If you have any questions or need to change your appointment, reply to this email.</p>
<br>
<p>Cheers</p>
<p>Paolo Catalan</p>
