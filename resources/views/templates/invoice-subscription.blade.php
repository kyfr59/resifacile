@inject('accountingSettings', 'App\Settings\AccountingSettings')
@inject('subscriptionSettings', 'App\Settings\SubscriptionSettings')
@inject('accounting', 'App\Helpers\Accounting')

@extends('templates.invoice-layout', [
    'number' => $number,
    'customer' => $data->transactionable->customer,
    'withSubscription' => $data->transactionable->with_subscription,
    'subscriptionSettings' => $subscriptionSettings,
])

@section('main')
    <td valign="top">
        <div style="border: 1px solid #1d4ed8;">
            <div style="background-color: #1d4ed8; padding: 8px;">
                <table width="100%">
                    <tr>
                        <td style="color: white; padding-right: 12px;">MONTANT TTC</td>
                        <td style="background-color: white; text-align: right; padding: 12px; font-weight: bold; font-size: 22px;">{{ number_format($data->amount/100, 2, ',', ' ') }}€</td>
                    </tr>
                </table>
            </div>
            <div style="padding: 10px;">
                <table cellpadding="0" cellspacing="6" width="100%" style="font-size: .8rem; line-height: 1rem;">
                    <tr>
                        <td style="font-size: .8rem; color: #1d4ed8; font-weight: bold;">Abonnement</td>
                        <td style="font-size: .8rem; color: #1d4ed8;" align="right">P.U.</td>
                        <td style="font-size: .8rem; color: #1d4ed8;" align="center">Qté</td>
                        <td style="font-size: .8rem; color: #1d4ed8;" align="right">Total</td>
                    </tr>
                    <tr>
                        <td>Abonnement service accès+</td>
                        <td style="width: 60px;" align="right">{{ number_format($data->amount/100, 2, ',', ' ') }} €</td>
                        <td style="width: 40px;" align="center">1</td>
                        <td style="width: 60px;" align="right">{{ number_format($data->amount/100, 2, ',', ' ') }} €</td>
                    </tr>
                    <tr><td colspan="4" height="20"></td></tr>
                    <tr style="font-size: 1rem">
                        <td align="right" colspan="3" style="font-weight: bold; color: #1d4ed8;">Montant TTC</td>
                        <td style="width: 60px;" align="right">{{ number_format($data->amount/100, 2, ',', ' ') }} €</td>
                    </tr>
                    <tr style="font-size: 1rem">
                        <td align="right" colspan="3" style="font-weight: bold; color: #1d4ed8;">Dont TVA ({{ $data->transactionable->vat_rate }}%)</td>
                        <td style="width: 60px;" align="right">{{ number_format(($data->amount - ($data->amount/1.2))/100, 2, ',', ' ') }} €</td>
                    </tr>
            </table>
        </div>
        </div>
    </td>
@endsection
