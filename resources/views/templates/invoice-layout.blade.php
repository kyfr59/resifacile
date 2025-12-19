<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Template invoice</title>
        <style>
            html, body {
                font-family: sans-serif;
                line-height: 1.6rem;
                color: #3e4b55;
                font-size: 14px;
            }
            @if(get_class($data->transactionable) === 'App\Models\Order' && $data->transactionable->customer->orders()->where('status', \App\Enums\OrderStatus::PAID)->count() <= 1)
                @page {
                    margin: 1cm 1cm 3cm 1cm !important;
                    padding: 0 !important;
                }
            @else
                @page {
                margin: 1cm 1cm 1cm 1cm !important;
                padding: 0 !important;
            }
            @endif
        </style>
    </head>
    <body style="position: relative;">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td style="width: 35%;">
                    <img src="{{ asset('images/logo.jpg') }}" width="100%" alt="">
                </td>
                <td align="right" style="color: #1d4ed8;">
                    <div>En date du {{ now()->format('d/m/Y') }}</div>
                    <div style="font-size: 22px;">Facture n° {{ $number }}</div>
                </td>
            </tr>
            <tr><td height="60" colspan="2"></td></tr>
            <tr>
                <td></td>
                <td style="color: #1d4ed8;">
                    @if($customer->compagny)
                        <div style="font-size: 18px;">{{ $customer->compagny }}</div>
                    @endif
                    <div style="font-size: 18px;">{{ $customer->name }}</div>
                    <div>{{ $customer->email }}</div>
                    <div>
                        @php
                            $address = $customer->addresses()->where('is_billing_address', true)->first() ?? $customer->addresses()->first();
                        @endphp
                        @if($address->address_line_2)
                            {{ $address->address_line_2 }}<br/>
                        @endif
                        {{ $address->address_line_4 }}<br/>
                        {{ $address->postal_code }} {{ $address->city }}
                    </div>
                </td>
            </tr>
            <tr><td height="40" colspan="2"></td></tr>
            <tr>
                <td valign="top">
                    <div style="margin-right: 24px; border: 1px solid #1d4ed8;">
                        <div style="color: white; background-color: #1d4ed8; padding: 12px; display: flex; align-items: center; line-height: 1rem;">
                            Vos contacts utiles
                        </div>
                        <div style="padding: 24px;">
                            <div style="line-height: 1.2em; font-size: 11px;margin-bottom: 6px;">
                                <div style="font-size: 10px;font-weight: bold; color:#1d4ed8;">Retrouvez votre espace client sur :</div>
                                <div>{{ url('mon-compte') }}</div>
                            </div>
                            <div style="font-size: 11px; line-height: 1.2rem; line-height: 1.2em;margin-bottom: 6px;">
                                <div style="font-size: 10px;font-weight: bold; color:#1d4ed8;">Nous contactez :</div>
                                <div>{{ config('mail.from.address') }}</div>
                            </div>
                            <div style="text-align: justify; font-size: 8px; line-height: 1.4em;">Pour toute demande spécifique, n'hésitez pas à contacter notre service d'aides disponible 7 jours sur 7 depuis le site {{ config('app.name') }}. Connectez-vous depuis votre compte {{ route('login') }}  et accédez facilement à vos factures et vos options d'abonnement.</div>
                        </div>
                    </div>
                </td>
                @yield('main')
            </tr>
            <tr><td height="24" colspan="2"></td></tr>
            <tr>
                <td colspan="2" style="border: 1px solid #dddddd; width: 100%;padding: 24px;font-size: 12px;">
                    <div style="margin-bottom: 6px; line-height: 1.2em;">
                        <div>
                            <div style="font-size: 10px; font-weight: bold; color:#1d4ed8;">Pour toute question :</div>
                            <div style="font-size: 12px;">Contactez-nous sur contact@stop-contrat.com ou par téléphone au :</div>
                            <div>
                                <img src="numero-vert.png" height="35" alt="">
                            </div>
                        </div>
                    </div>
                    <div style="margin-bottom: 6px; line-height: 1.2em;">
                        <div>
                            <div style="font-size: 10px; font-weight: bold; color:#1d4ed8;">Accèdez à vos factures :</div>
                            <div style="font-size: 12px;">depuis votre espace client {{ url('mon-compte') }}</div>
                        </div>
                    </div>
                    <div style="line-height: 1.2rem;">
                        <div style="font-size: 12px; font-weight: bold; color:#1d4ed8;">Pensez à ne pas imprimer votre facture afin de préserver l'environnement</div>
                    </div>
                </td>
            </tr>
        </table>
        <table style="position: absolute; bottom: 1cm; width: 100%;">
            @if(get_class($data->transactionable) === 'App\Models\Order' && $data->transactionable->customer->orders()->where('status', \App\Enums\OrderStatus::PAID)->count() <= 1)
                <tr>
                    <td style="font-size: .5rem; text-align: justify; line-height: .7rem;">
                        {{ $subscriptionSettings->mention_one }}
                    </td>
                </tr>
                <tr><td height="10"></td></tr>
            @endif
            <tr>
                <td style="font-size: .4rem; line-height: 1.4em; text-align: center;">
                    {{ config('app.name') }} - Euro CB SAS - Capital Social 30 000€ - RCS 821 030 574 Bordeaux - APE 6312Z<br/>
                    TVA FR 28 821 030 574 - 2, rue Buhan, 33000 Bordeaux - contact@stop-contrat.com
                </td>
            </tr>
        </table>
    </body>
</html>
