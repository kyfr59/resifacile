<x-mail::message>
Chère cliente, cher client,

Merci pour votre commande récente sur {{ config('mail.from.name') }}.

Avez-vous été satisfait ?

<x-mail::button :url="'https://fr.trustpilot.com/evaluate/' . config('mail.from.name')">
    Donnez votre avis
</x-mail::button>

Votre expérience client est importante pour nous et votre avis sera immédiatement publié pour aider les autres clients à prendre leur décision.

Merci pour votre aide.

Bien à vous,<br>
L'équipe de {{ config('app.name') }}
</x-mail::message>
