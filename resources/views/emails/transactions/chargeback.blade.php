<x-mail::message>
Bonjour,

Nous avons reçu un nouveau chargeback concernant l'abonnement id numéro {{ $transaction->transactionable->id }}, ayant comme numéro de transaction {{ $transaction->transaction_id }}.

<x-mail::button url="#">
    Accèder à la transaction
</x-mail::button>

Détail client :

- **Id** : {{ $transaction->transactionable->customer->id }}
@if($transaction->transactionable->customer->compagny)
- **Société** : {{ $transaction->transactionable->customer->compagny }}
@endif
- **Nom** : {{ $transaction->transactionable->customer->name }}
- **Mail** : {{ $transaction->transactionable->customer->email }}
@if($transaction->transactionable->customer->phone)
- **Téléphone** : {{ $transaction->transactionable->customer->phone }}
@endif

<x-mail::button url="#">
    Accèder à la fiche client
</x-mail::button>

Bonne journée,<br>
{{ config('app.name') }}
</x-mail::message>
