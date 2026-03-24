@php
    $sender = $data->data->requests[0]->senders[0]->paper_address;
    $recipient = $data->data->requests[0]->recipients[0]->paper_address;
@endphp

<x-mail::message>
Chère cliente, cher client,

Nous avons le plaisir de vous informer que votre commande n° {{ $data->order->number }} a été transmise à notre partenaire logistique, Docapost, pour entamer le processus de production. Les différentes étapes de ce processus sont les suivantes :

1. Impression de vos documents ;
2. Mise sous pli et affranchissement conformément à vos choix d'options ;
3. Dépôt à La Poste en vue de l'acheminement vers votre destinataire.

Veuillez noter que le processus de production peut prendre jusqu'à 24 heures avant le dépôt effectif à La Poste. Pour les commandes passées le vendredi après-midi, le dépôt aura lieu le lundi suivant.

**Information d'expédition :**
<br>
<table style="width: 100%; background-color: #fafafa; font-size: 12px;" cellpadding="0" cellspacing="0">
    <tr>
        <th align="center" style="width: 50%; padding:16px  8px;">Adresse expéditeur</th>
    </tr>
    <tr>
        <td style="padding: 2px 8px;"valign="top">
            @if($sender->address_lines->address_line_1)
                {{ $sender->address_lines->address_line_1 }}<br>
            @endif
            @if($sender->address_lines->address_line_2)
                {{ $sender->address_lines->address_line_2 }}<br>
            @endif
            @if($sender->address_lines->address_line_3)
                {{ $sender->address_lines->address_line_3 }}<br>
            @endif
            @if($sender->address_lines->address_line_4)
                {{ $sender->address_lines->address_line_4 }}<br>
            @endif
            @if($sender->address_lines->address_line_5)
                {{ $sender->address_lines->address_line_5 }}<br>
            @endif
            @if($sender->address_lines->address_line_6)
                {{ $sender->address_lines->address_line_6 }}<br>
            @endif
            {{ $sender->country }}
        </td>
    </tr>
    <tr><td align="center" colspan="2" height="10"></td></tr>
</table>
<br>
<table style="width: 100%; background-color: #fafafa; font-size: 12px;" cellpadding="0" cellspacing="0">
    <tr>
        <th align="center" style="width: 50%; padding:16px  8px;">Adresse destinataire</th>
    </tr>
    <tr>
        <td style="padding: 2px 8px;"valign="top">
            @if($recipient->address_lines->address_line_1)
                {{ $recipient->address_lines->address_line_1 }}<br>
            @endif
            @if($recipient->address_lines->address_line_2)
                {{ $recipient->address_lines->address_line_2 }}<br>
            @endif
            @if($recipient->address_lines->address_line_3)
                {{ $recipient->address_lines->address_line_3 }}<br>
            @endif
            @if($recipient->address_lines->address_line_4)
                {{ $recipient->address_lines->address_line_4 }}<br>
            @endif
            @if($recipient->address_lines->address_line_5)
                {{ $recipient->address_lines->address_line_5 }}<br>
            @endif
            @if($recipient->address_lines->address_line_6)
                {{ $recipient->address_lines->address_line_6 }}<br>
            @endif
            {{ $recipient->country }}
        </td>
    </tr>
    <tr><td align="center" colspan="2" height="10"></td></tr>
</table>
<br>

**Pour les envois en recommandé avec accusé de réception :**

- Votre numéro de suivi sera envoyé par courrier électronique et éventuellement par SMS dans un délai de 24 heures suivant l'affranchissement de vos documents. L'activation de ce numéro interviendra quelques heures après le dépôt de votre courrier à La Poste ;
- Vous recevrez une confirmation de dépôt exclusivement par courrier électronique. Cette confirmation comprendra la preuve électronique du dépôt de votre lettre recommandée électronique, ainsi que sa représentation graphique associée.

Le délai de livraison de vos documents à leur destinataire commence dès le dépôt à La Poste et est généralement de 3 jours ouvrés, auxquels s'ajoutent les éventuels week-ends et jours fériés.

En cas de recommandé avec accusé de réception, veuillez noter que l'avis d'accusé de réception sera déposé dans votre boîte aux lettres dans un délai d'environ 7 à 10 jours ouvrés par le facteur de votre commune.

Nous restons à votre disposition pour toute question ou clarification supplémentaire.

Madame, Monsieur, toute l’équipe {{ config('app.name') }} vous remercie pour votre confiance.

Bien à vous,<br>
L'équipe de {{ config('app.name') }}
</x-mail::message>
