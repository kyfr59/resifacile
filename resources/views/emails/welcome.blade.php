<x-mail::message>
Bienvenue sur {{ config('app.name') }},

Merci pour votre inscription !

Vous trouverez ci-dessous votre identifiant de connexion :
Identifiant : {{ $data->transactionable->customer->email }}

Votre espace client est accessible à l'adresse suivante :

<x-mail::button url="{{ url('login') }}">
    Mon compte
</x-mail::button>

Pour vous identifier, vous recevrez un email contenant un lien sécurisé qui vous permettra d'accéder à votre compte.

Pour toutes demandes supplémentaires, notre service client se tient à votre entière disposition du lundi au vendredi de 08h00 à 20h00 par téléphone au 0 800 942 588 (appel gratuit) ou par email à {{ config('mail.from.address') }}.

Merci de votre confiance et à bientôt sur {{ config('app.name') }}

Bien à vous,<br>
L'équipe d'{{ config('app.name') }}
</x-mail::message>
