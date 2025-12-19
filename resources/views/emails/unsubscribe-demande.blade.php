<x-mail::message>
Chère cliente, cher client,

Pour traiter votre demande de résiliation de manière efficace, nous vous prions de bien vouloir cliquer sur le bouton ci-dessous afin de confirmer votre demande :

<x-mail::button :url="$url">
    Confirmer la résiliation mon abonnement accès+
</x-mail::button>

Veuillez noter que cette action entraînera la résiliation immédiate de votre abonnement au service accès+. Si vous avez des questions ou des préoccupations supplémentaires, notre service client se tient à votre entière disposition du lundi au vendredi de 08h00 à 20h00 par téléphone au 0 800 942 588 (appel gratuit) ou par email à {{ config('mail.from.address') }}.

Bien à vous,<br>
L'équipe de {{ config('app.name') }}
</x-mail::message>
