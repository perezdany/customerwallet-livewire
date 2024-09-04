@component('mail::message')
# A ne pas répondre!
	
Merci de ne pas répondre à ce mail.

Bonjour <b>{{$data['nom']}}</b>! Voici votre code de connexion: <b>{{$data['code']}}</b> <br>Veuillez entrer ce code pour vous connecter


Cordialement,<br>
{{ config('app.name') }}
@endcomponent
