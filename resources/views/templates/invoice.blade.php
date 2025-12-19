@inject('accountingSettings', 'App\Settings\AccountingSettings')
@inject('subscriptionSettings', 'App\Settings\SubscriptionSettings')
@inject('accounting', 'App\Helpers\Accounting')

@extends('templates.invoice-layout', [
    'number' => $number,
    'customer' => $data->transactionable->customer,
    'withSubscription' => $data->transactionable->with_subscription,
    'subscriptionSettings' => $subscriptionSettings,
])

@php
    $number_of_recipients = collect($data->transactionable->details->recipients)->count();
    $number_of_pages = collect($data->transactionable->details->documents)->sum('number_of_pages');
    $weight = App::make(\App\Settings\WeightSettings::class);
    $weight = $number_of_pages * $weight->paper[80] + $weight->envelope['C6'];
    $priceFold = 0;
    $priceReceipt = 0;
    $weightFold = 0;
    foreach ($data->transactionable->details->pricing_postage as $wFold => $price) {
        if ($weight <= $wFold) {
            $priceFold = $price;
            $weightFold = $wFold;
            break;
        }
    }
@endphp

@section('main')
    <td valign="top">
        <div style="border: 1px solid #1d4ed8;">
            <div style="background-color: #1d4ed8; padding: 8px;">
                <table width="100%">
                    <tr>
                        <td style="color: white; padding-right: 12px;">MONTANT TTC</td>
                        <td style="background-color: white; text-align: right; padding: 12px; font-weight: bold; font-size: 22px;">{{ number_format($data->amount / 100, 2, ',', ' ') }}€</td>
                    </tr>
                </table>
            </div>
            <div style="padding: 10px;">
                <table cellpadding="0" cellspacing="6" width="100%" style="font-size: .8rem; line-height: 1rem;">
                    <tr>
                        <td style="font-size: .8rem; color: #1d4ed8; font-weight: bold;">Impression</td>
                        <td style="font-size: .8rem; color: #1d4ed8;" align="right">P.U.</td>
                        <td style="font-size: .8rem; color: #1d4ed8;" align="center">Qté</td>
                        <td style="font-size: .8rem; color: #1d4ed8;" align="right">Total</td>
                    </tr>
                    @foreach($data->transactionable->options as $option)
                        @if($option->name === 'color_print' || $option->name === 'black_print')
                            <tr>
                                <td>Impression 1<sup>ère</sup> page {{ ($option->name === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $data->transactionable->options, true) ? 'R°V°' : '' }}</td>
                                <td style="width: 60px;" align="right">@price($accounting->withSubscription($option->price[0], $data->transactionable->with_subscription))</td>
                                <td style="width: 40px;" align="center">{{ $number_of_recipients }}</td>
                                <td style="width: 60px;" align="right">@price($accounting->withSubscription($option->price[0] * $number_of_recipients, $data->transactionable->with_subscription))</td>
                            </tr>
                            @if($number_of_pages > 1)
                                <tr>
                                    <td>Impression page suivante {{ ($option->name === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $data->transactionable->options, true) ? 'R°V°' : '' }}</td>
                                    <td style="width: 60px;" align="right">@price($accounting->withSubscription($option->price[1], $data->transactionable->with_subscription))</td>
                                    <td style="width: 40px;" align="center">{{ ($number_of_pages - 1) * $number_of_recipients }}</td>
                                    <td style="width: 60px;" align="right">@price($accounting->withSubscription($option->price[1] * ($number_of_pages - 1) * $number_of_recipients, $data->transactionable->with_subscription))</td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="4" style="padding-top: 10px;font-weight: bold;color: #1d4ed8;">Affranchissement</td>
                    </tr>
                    <tr>
                        <td>Affr. {{ $weightFold }}g : {{ Str::lower($data->transactionable->postage->label()) }}</td>
                        <td align="right">@price($accounting->withSubscription($priceFold, $data->transactionable->with_subscription))</td>
                        <td align="center">{{ $number_of_recipients }}</td>
                        <td align="right">@price($accounting->withSubscription($priceFold * $number_of_recipients, $data->transactionable->with_subscription))</td>
                    </tr>
                    @if(collect($data->transactionable->options)->filter(fn ($option) => $option->name === 'receipt' || $option->name === 'sms_notification')->count() > 0 || ($data->transactionable->with_subscription && $data->transactionable->customer->orders()->where('status', \App\Enums\OrderStatus::PAID)->count() <= 1))
                        <tr>
                            <td colspan="4" style="padding-top: 10px;font-weight: bold;color: #1d4ed8;">Options complémentaires</td>
                        </tr>
                    @endif
                    @foreach($data->transactionable->options as $option)
                        @if($option->name === 'receipt' || $option->name === 'sms_notification')
                            <tr>
                                @if($option->name === 'receipt')
                                    <td>Justificatif d'accusé réception</td>
                                @elseif($option->name === 'sms_notification')
                                    <td>Notification SMS</td>
                                @endif
                                <td align="right">@price($accounting->withSubscription($option->price, $data->transactionable->with_subscription))</td>
                                <td align="center">{{ $number_of_recipients }}</td>
                                <td align="right">@price($accounting->withSubscription($option->price * $number_of_recipients, $data->transactionable->with_subscription))</td>
                            </tr>
                        @endif
                    @endforeach
                    @if($data->transactionable->with_subscription && $data->transactionable->customer->orders()->where('status', \App\Enums\OrderStatus::PAID)->count() <= 1)
                        <tr>
                            <td>Service accès+ offert pendant 15 jours, puis 39,90€/mois</td>
                            <td align="right">@price(0)</td>
                            <td align="center">1</td>
                            <td align="right">@price(0)</td>
                        </tr>
                    @endif
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
