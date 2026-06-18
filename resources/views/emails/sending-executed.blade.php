@php
    $sender = $data->data->requests[0]->senders[0]->paper_address;
    $recipient = $data->data->requests[0]->recipients[0]->paper_address;
@endphp

<x-mail::message>
Chère cliente, cher client,

Nous avons le plaisir de vous informer que votre commande n°COM0000000079 a été transmise à notre partenaire Docaposte, filiale du groupe La Poste, pour entamer le processus de production.

Les différentes étapes de ce processus de mise en production sont les suivantes :
<ul>
<li>Impression de vos documents ;</li>
<li>Mise sous pli et affranchissement conformément à vos choix d'options ;</li>
<li>Dépôt à La Poste en vue de l'acheminement vers votre destinataire.</li>
</ul>

Le processus de production nécessite généralement quelques heures et peut, dans certains cas, prendre jusqu'à 24 heures avant le dépôt à La Poste. Pour les commandes passées le vendredi après-midi, le dépôt aura lieu le lundi suivant.

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

<strong>Pour les envois en recommandé avec accusé de réception :</strong>

Votre numéro de suivi vous sera envoyé par e-mail dans un délai de 24 heures suivant l'affranchissement de vos documents. L'activation de ce numéro interviendra quelques heures après le dépôt de votre courrier à La Poste ;

Une confirmation de dépôt vous sera ensuite adressée par courrier électronique. Cette confirmation comprendra la preuve électronique du dépôt de votre lettre recommandée électronique, ainsi que sa représentation graphique associée.

Vous recevrez également une preuve de contenu attestant du contenu de votre envoi. Ce document peut être utile pour démontrer juridiquement les documents transmis.

Enfin, votre accusé de réception sera envoyé par email (ou la preuve de non distribution le cas échéant), ce dernier sera également disponible dans votre espace client.

Le délai de livraison de vos documents à leur destinataire commence dès le dépôt à La Poste et est généralement de 3 jours ouvrés à compter du dépôt à La Poste, auxquels s'ajoutent les éventuels week-ends et jours fériés.

Nous restons à votre disposition pour toute question ou clarification supplémentaire.

Madame, Monsieur, toute l’équipe de Resifacile vous remercie pour votre confiance.

Bien cordialement,<br />
L'équipe de Resifacile
</x-mail::message>
