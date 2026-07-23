<x-mail::message>
Chère cliente, cher client,

Nous vous informons que votre courrier numéro {{$order_number}}, associé au numéro de suivi {{$number}}, est actuellement en cours d’acheminement dans le réseau de La Poste.

Il transite par les plateformes logistiques avant d’être envoyé vers son site de distribution. Son suivi sera mis à jour au fur et à mesure de son avancée.

Vous pouvez suivre son acheminement en détail à l’adresse suivante :
{{ config('app.url') }}/suivre-votre-envoi/{{$number}}

Aucune action n’est nécessaire de votre part pour le moment. Nous vous informerons des prochaines étapes importantes de sa distribution.

Nous vous remercions pour votre confiance.

Bien cordialement,

L’équipe Resifacile
</x-mail::message>
