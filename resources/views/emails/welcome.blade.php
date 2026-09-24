<x-mail::message>
Bienvenue sur Resifacile,

Merci pour votre inscription !

Vous trouverez ci-dessous votre identifiant de connexion :<br />
Identifiant : {{ $data->transactionable->customer->email }}

Votre espace client est accessible à l'adresse suivante :

<x-mail::button url="{{ url('login') }}">
    Mon compte
</x-mail::button>

Pour vous identifier, vous recevrez un email contenant un lien sécurisé qui vous permettra d'accéder à votre compte.

Pour toute question concernant votre commande, le suivi de votre courrier ou votre facture, notre service client est à votre disposition par téléphone du lundi au vendredi, de 09h30 à 17h00, au 0805 690 500 (appel gratuit) ou par e-mail à contact@resifacile.fr. Vous pouvez aussi gérer ou résilier votre abonnement accès+ en ligne à tout moment en cliquant <a target="_blank" href="https://resifacile.fr/se-desabonner">ici</a>. Si vous avez besoin d’aide pour rédiger une lettre ou utiliser notre service en ligne, notre équipe peut également vous accompagner.

Merci de votre confiance et à bientôt sur Resifacile

Bien à vous,<br>
L'équipe Resifacile<br>
https://resifacile.fr
</x-mail::message>
