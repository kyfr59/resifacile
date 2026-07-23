<x-mail::message>
Chère cliente, cher client,

Nous vous informons que votre courrier n°{{$order_number}} n’a pas pu être remis à son destinataire. Il va donc être retourné à notre partenaire logistique.

Dès que le pli sera retourné, réceptionné puis numérisé par notre partenaire logistique, une preuve de non-distribution vous sera envoyée par e-mail.

Pour rappel, vous pouvez suivre le retour de votre courrier sur  {{ config('app.url') }}/suivre-votre-envoi/{{$number}}

La mise à disposition de cette preuve peut parfois prendre jusqu’à 15 jours, le temps nécessaire à l’acheminement du pli retourné, à sa réception et à sa numérisation.

Cette preuve confirmera que votre courrier n’a pas pu être distribué et vous indiquera le motif du retour communiqué par La Poste, par exemple un courrier non réclamé, une adresse incorrecte, un destinataire inconnu à l’adresse indiquée ou un refus du courrier.

Nous vous remercions pour votre confiance.

Bien cordialement,

L’équipe Resifacile
</x-mail::message>
