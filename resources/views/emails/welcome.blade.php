<x-mail::message>
Bienvenue sur Résifacile,

Merci pour votre inscription !

Vous trouverez ci-dessous votre identifiant de connexion :<br />
Identifiant : {{ $data->transactionable->customer->email }}

Votre espace client est accessible à l'adresse suivante :

<x-mail::button url="{{ url('login') }}">
    Mon compte
</x-mail::button>

Pour vous identifier, vous recevrez un email contenant un lien sécurisé qui vous permettra d'accéder à votre compte.

Pour toutes demandes supplémentaires, notre service client se tient à votre entière disposition du lundi au vendredi de 08h00 à 20h00 par téléphone au 0805 690 500 (appel gratuit) ou par email à {{ config('mail.from.address') }}.

Merci de votre confiance et à bientôt sur Résifacile

Bien à vous,<br>
L'équipe Résifacile<br>
https://resifacile.fr
</x-mail::message>
