@component('mail::message')
# {{ $details['title'] }}
  
You can reset password from bellow link
   
@component('mail::button', ['url' => $details['url']])
Reset password link
@endcomponent
   
Thanks,<br>
{{ config('app.name') }}
@endcomponent