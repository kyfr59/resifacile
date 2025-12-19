Bonjour,

Nous vous confirmons que votre demande a bien été effectuée et que nous avons procédé au remboursement de votre transaction n° {{ $transaction->transaction_id }} éffectuée ce jour ({{ now()->format('d/m/Y') }}) pour un montant de {{ \App\Helpers\Accounting::priceFormat($transaction->amount) }}.

Le montant de {{ \App\Helpers\Accounting::priceFormat($transaction->amount) }} vous sera recrédité sur votre compte dans 5 à 7 jours ouvrés.

Bien à vous,
L'équipe de {{ config('app.name') }}
