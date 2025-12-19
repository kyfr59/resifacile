<x-mail::message>
Bonjour,

Vous souhaitez vous connecter à votre compte.
Veuillez cliquer sur le bouton ci-dessous pour y accéder :

<x-mail::button :url="$url">
    J'accède à mon compte
</x-mail::button>

Attention, le lien n'est valide que pendant 10 minutes par mesure de sécurité.
Après ce laps de temps, veuillez refaire une demande.

Bien à vous,<br>
L'équipe de {{ config('app.name') }}
</x-mail::message>
