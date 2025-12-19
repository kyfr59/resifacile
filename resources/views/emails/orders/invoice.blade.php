@inject('accountingSettings', 'App\Settings\AccountingSettings')
@inject('subscriptionSettings', 'App\Settings\SubscriptionSettings')
@inject('accounting', 'App\Helpers\Accounting')

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
    $address = $data->transactionable->customer->addresses()->where('is_billing_address', true)->first() ?? $data->transactionable->customer->addresses()->first();
@endphp

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        a { color: #000 }
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>
<body style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #fff; color: #000; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;font-size: 14px;">

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #fff; margin: 0; padding: 0; width: 100%;">
    <tr>
        <td align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                <tr>
                    <td class="header" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; padding: 25px 0; text-align: center;">
                        <a href="{{ config('app.url') }}" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 19px; font-weight: bold; text-decoration: none; display: inline-block;">
                            <img src="{{ asset('images/logo.jpg') }}" class="logo" alt="{{ config('app.name') }}" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100%; border: none; height: 45px; max-height: 45px;">
                        </a>
                    </td>
                </tr>
                <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #fff; margin: 0; padding: 0; width: 100%; border: hidden !important;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; margin: 0 auto; padding: 0; width: 570px;">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px;text-align: justify;">
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        Chère cliente, cher client,
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        Nous vous remercions pour la commande n° {{ $data->transactionable->number }}, effectuée sur notre site {{ config('app.name') }}.
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        Votre courrier a bien été pris en charge par nos services et votre paiement a bien été accepté.
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        Si vous avez opté pour un recommandé avec AR, vous recevrez un e-mail contenant le numéro de suivi dès que votre courrier aura été pris en charge par La Poste. En cas de non-réception, n’hésitez pas à contacter notre service client qui se tient à votre disposition.
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        Vous trouverez par ailleurs, ci-dessous, le récapitulatif de votre commande.
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        Pour toutes demandes supplémentaires, notre service client se tient à votre entière disposition du lundi au vendredi de 08h00 à 20h00 par téléphone au 0 800 942 588 (appel gratuit) ou par email à {{ config('mail.from.address') }}.
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        <table style="width: 100%; background-color: #fafafa; font-size: 12px;" cellpadding="0" cellspacing="0">
                                            <tr><th align="center" colspan="4" style="padding:16px  8px;">Information de facturation</th></tr>
                                            @if($address->compagny)
                                                <tr>
                                                    <td style="padding: 2px 8px;"><strong>Société</strong></td>
                                                    <td style="padding: 2px 8px;">{{ $address->compagny }}</td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td style="padding: 2px 8px;"><strong>Prénom</strong></td>
                                                <td style="padding: 2px 8px;">{{ $address->first_name }} </td>
                                                <td style="padding: 2px 8px;"><strong>Adresse</strong></td>
                                                <td style="padding: 2px 8px;">{{ $address->address_line_4 }}</td>

                                            </tr>
                                            <tr>
                                                <td style="padding: 2px 8px;"><strong>Nom</strong></td>
                                                <td style="padding: 2px 8px;">{{ $address->last_name }}</td>
                                                <td style="padding: 2px 8px;"><strong>Code postal</strong></td>
                                                <td style="padding: 2px 8px;">{{ $address->postal_code }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 2px 8px;"><strong>Email</strong></td>
                                                <td style="padding: 2px 8px;">{{ $data->transactionable->customer->email }}</td>
                                                <td style="padding: 2px 8px;"><strong>Commune</strong></td>
                                                <td style="padding: 2px 8px;">{{ $address->city }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td style="padding: 2px 8px;"><strong>Pays</strong></td>
                                                <td style="padding: 2px 8px;">{{ $address->country }}</td>
                                            </tr>
                                            <tr><td align="center" colspan="2" height="10"></td></tr>
                                        </table>
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        <table style="width: 100%; background-color: #fafafa; font-size: 12px;" cellpadding="0" cellspacing="0">
                                            <tr><td colspan="4" height="10"></td></tr>
                                            @foreach($data->transactionable->options as $option)
                                                @if($option->name === 'color_print' || $option->name === 'black_print')
                                                    <tr>
                                                        <td style="padding: 2px 8px;">Impression 1<sup>ère</sup> page {{ ($option->name === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $data->transactionable->options, true) ? 'R°V°' : '' }}</td>
                                                        <td style="padding: 2px 8px; text-align: right;">@price($accounting->withSubscription($option->price[0], $data->transactionable->with_subscription))</td>
                                                        <td style="padding: 2px 8px; text-align: center;">{{ $number_of_recipients }}</td>
                                                        <td style="padding: 2px 8px; text-align: right;">@price($accounting->withSubscription($option->price[0] * $number_of_recipients, $data->transactionable->with_subscription))</td>
                                                    </tr>
                                                    @if($number_of_pages > 1)
                                                        <tr>
                                                            <td style="padding: 2px 8px;">Impression page suivante {{ ($option->name === 'color_print')?'couleur':'N/B' }} {{ in_array('recto_verso', $data->transactionable->options, true) ? 'R°V°' : '' }}</td>
                                                            <td style="padding: 2px 8px; text-align: right;">@price($accounting->withSubscription($option->price[1], $data->transactionable->with_subscription))</td>
                                                            <td style="padding: 2px 8px; text-align: center;">{{ ($number_of_pages - 1) * $number_of_recipients }}</td>
                                                            <td style="padding: 2px 8px; text-align: right;">@price($accounting->withSubscription($option->price[1] * ($number_of_pages - 1) * $number_of_recipients, $data->transactionable->with_subscription))</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach
                                            <tr>
                                                <td style="padding: 2px 8px; vertical-align: top;">Affr. {{ $weightFold }}g : {{ Str::lower($data->transactionable->postage->label()) }}</td>
                                                <td style="padding: 2px 8px; vertical-align: top; text-align: right;">@price($accounting->withSubscription($priceFold, $data->transactionable->with_subscription))</td>
                                                <td style="padding: 2px 8px; vertical-align: top; text-align: center;">{{ $number_of_recipients }}</td>
                                                <td style="padding: 2px 8px; vertical-align: top; text-align: right;">@price($accounting->withSubscription($priceFold * $number_of_recipients, $data->transactionable->with_subscription))</td>
                                            </tr>
                                            @foreach($data->transactionable->options as $option)
                                                @if($option->name === 'receipt' || $option->name === 'sms_notification')
                                                    <tr>
                                                        @if($option->name === 'receipt')
                                                            <td style="padding: 2px 8px; vertical-align: center;">Justificatif d'accusé réception</td>
                                                        @elseif($option->name === 'sms_notification')
                                                            <td style="padding: 2px 8px; vertical-align: center;">Notification SMS</td>
                                                        @endif
                                                        <td style="padding: 2px 8px; vertical-align: center; text-align: right;">@price($accounting->withSubscription($option->price, $data->transactionable->with_subscription))</td>
                                                        <td style="padding: 2px 8px; vertical-align: center; text-align: center;">{{ $number_of_recipients }}</td>
                                                        <td style="padding: 2px 8px; vertical-align: center; text-align: right;">@price($accounting->withSubscription($option->price * $number_of_recipients, $data->transactionable->with_subscription))</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @if($data->transactionable->with_subscription && $data->transactionable->customer->orders()->where('status', \App\Enums\OrderStatus::PAID)->count() <= 1)
                                                <tr>
                                                    <td style="padding: 2px 8px; vertical-align: top;">Service accès+ sans engagement,<br>offert 15 jours puis 39,90€/mois*</td>
                                                    <td style="padding: 2px 8px; vertical-align: top; text-align: right;"></td>
                                                    <td style="padding: 2px 8px; vertical-align: top; text-align: center;"></td>
                                                    <td style="padding: 2px 8px; vertical-align: top; text-align: right;">@price(0)</td>
                                                </tr>
                                            @endif
                                            <tr><td colspan="4" height="10"></td></tr>
                                            <tr>
                                                <td colspan="3" style="vertical-align: center; font-size: 23px; padding: 8px; text-align: right; background-color: #f8f2e8;">
                                                    <div style="line-height: 1.2rem; margin-top: 7px;">
                                                        <div style="font-weight: bold; color: #e07562;">Montant total</div>
                                                        <div style="font-size: 12px; color: #000;">Réglé aujourd'hui</div>
                                                    </div>
                                                </td>
                                                <td style="vertical-align: center; font-size: 42px; color: #000; background-color: #f8f2e8; padding: 8px; text-align: right; font-weight: bold;">{{ number_format($data->amount/100, 2, ',', ' ') }} €</td>
                                            </tr>
                                        </table>
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        Merci de votre confiance et à bientôt sur {{ config('app.name') }}
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                                        Cordialement,<br>
                                        L'équipe {{ config('app.name') }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #fff; margin: 0; padding: 0; width: 100%; border: hidden !important;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #eff2f1; margin: 0 auto; padding: 0; width: 570px;">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 0 0 0 0;">
                                    <img src="{{ asset('images/sav-small.jpg') }}" width="70%" alt="Vous avez des questions, faites appel à notre service client du lundi au vendredi de 8h à 20h"/>
                                </td>
                                <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 0 36px 0 0;">
                                    <h3 style="color:#87b33e;">Vous avez des questions sur <strong>votre commande</strong> ?</h3>
                                    <p>
                                        <a href="tel:0800942588" style="text-decoration: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 127.84 18.72" style="height: 40px;">
                                                <path fill="#ffffff" d="M0 1.99h127.84V16.8H0z"></path>
                                                <text transform="translate(3.25 13.35)" font-size="11" fill="#7ab51d" font-family="Arial-BoldMT,Arial" font-weight="700">0 800 942 588</text>
                                                <path fill="#7ab51d" d="M76.53 1.6v3.86l3.93 3.94-3.93 3.93v3.86h47.4V1.6h-47.4z"></path>
                                                <path fill="#ffffff" d="M82.27 4.85c0-.87.46-1.12 1.07-1.13a5.34 5.34 0 011.53.17v.66c-.35 0-1.13-.06-1.32-.06s-.53 0-.53.43v.21c0 .35.12.42.43.42h.63c.77 0 .93.68.93 1.17V7c0 1-.56 1.18-1.09 1.18A4.88 4.88 0 0182.39 8v-.65c.22 0 .88.07 1.38.07.22 0 .48 0 .48-.38V6.8c0-.25-.06-.41-.35-.41h-.61c-1 0-1-.78-1-1.16zM86.64 4.93c.83 0 1.22.11 1.22 1.16a.7.7 0 01-.8.8h-.91c0 .58.19.61.6.61a6.11 6.11 0 001.06-.06V8a4.27 4.27 0 01-1.47.17c-.73 0-1-.47-1-1.64.05-1.36.39-1.6 1.3-1.6zm.22 1.36a.22.22 0 00.24-.24c0-.4 0-.47-.41-.47s-.55 0-.54.71zM88.27 5h.63l.12.4a1.18 1.18 0 01.83-.44 1 1 0 01.32 0v.81h-.42a.74.74 0 00-.73.35v2h-.75zM91.16 5l.63 2.2.64-2.2h.81l-1 3.18h-.85L90.35 5zM93.52 4a.13.13 0 01.13-.15h.57c.08 0 .12.07.12.15v.53c0 .09 0 .14-.12.14h-.57a.13.13 0 01-.13-.14zm0 1h.76v3.14h-.76zM95.69 4.93a4.17 4.17 0 011.31.19v.52h-1c-.49 0-.52 0-.52 1s.13.94.52.94 1-.06 1-.06V8a2.65 2.65 0 01-1.36.2c-.53 0-.94-.31-.94-1.62s.44-1.65.99-1.65zM98.56 4.93c.83 0 1.22.11 1.22 1.16a.7.7 0 01-.8.8h-.92c0 .58.2.61.61.61a5.94 5.94 0 001-.06V8a4.21 4.21 0 01-1.47.17c-.73 0-.94-.47-.94-1.64.05-1.36.39-1.6 1.3-1.6zm.22 1.36a.22.22 0 00.22-.24c0-.4 0-.47-.42-.47s-.54 0-.54.71zM101.88 5.93l.37-.36-.25-.45a.86.86 0 01.42-1.36 3.42 3.42 0 011.5.12v.61H103c-.41 0-.46.18-.19.59l.91 1.39.66-.69.26.36-.32.64-.2.27.74 1.09H104l-.38-.57-.35.34a1.05 1.05 0 01-1.6-.26 1.33 1.33 0 01.21-1.72zm1 1.33l.27-.29-.54-.8-.28.27a.5.5 0 00-.09.73.39.39 0 00.66.09zM106.46 7a.79.79 0 01.85-.87h.84V6c0-.34-.15-.39-.39-.39s-1 .05-1.2.07v-.57a3.22 3.22 0 011.31-.21c.61 0 1 .23 1 1v2.24h-.59l-.16-.35a1.24 1.24 0 01-.87.39.81.81 0 01-.82-.85zm1 .47a1.37 1.37 0 00.66-.24v-.56l-.7.06c-.2 0-.23.19-.23.33v.15c.03.27.16.29.3.29zM109.35 5h.65l.14.32a1.39 1.39 0 01.86-.36c.71 0 .94.66.94 1.61s-.14 1.66-.94 1.66a2.09 2.09 0 01-.9-.23v1.76h-.75zm1.43 2.57c.24 0 .37-.15.37-1s-.13-.93-.37-.93a1.09 1.09 0 00-.67.2v1.57a1.54 1.54 0 00.67.12zM112.35 5h.65l.13.32a1.45 1.45 0 01.87-.36c.7 0 .93.66.93 1.61s-.14 1.66-.93 1.66a2.1 2.1 0 01-.9-.23v1.76h-.75zm1.43 2.57c.24 0 .38-.15.38-1s-.14-.93-.38-.93a1.11 1.11 0 00-.67.2v1.57a1.57 1.57 0 00.67.12zM116.57 4.93c.83 0 1.21.11 1.21 1.16a.7.7 0 01-.8.8h-.91c0 .58.19.61.6.61a6.11 6.11 0 001.06-.06V8a4.24 4.24 0 01-1.47.17c-.73 0-1-.47-1-1.64.05-1.36.4-1.6 1.31-1.6zm.22 1.36a.22.22 0 00.24-.24c0-.4-.06-.47-.42-.47s-.55 0-.54.71zM118.19 3.45h.81v3.43c0 .46.1.56.24.62a3.85 3.85 0 00.37.13v.51h-.63c-.47 0-.74-.29-.74-1.13zM82.52 13a1.14 1.14 0 01-.31-.88c0-.76.35-1.14 1-1.14H85v.51l-.32.1a1.42 1.42 0 01.11.63.94.94 0 01-1 1.07h-.67c-.08 0-.22 0-.22.14s.1.16.22.16H84c.52 0 .85.3.85.94v.35a.82.82 0 01-.9.92h-.81a.83.83 0 01-.89-.92v-.41l.25-.29a.7.7 0 01-.28-.61.56.56 0 01.3-.57zm.79-.39h.31c.28 0 .37-.2.36-.49s-.1-.46-.37-.46h-.29c-.3 0-.36.22-.36.46s.09.45.35.45zm0 2.52h.44c.27 0 .31-.15.31-.34v-.23c0-.18-.07-.29-.28-.29H82.98v.52c.02.26.15.3.34.3zM85.27 11h.64l.12.4a1.12 1.12 0 01.82-.44 1 1 0 01.32 0v.81h-.41a.72.72 0 00-.73.35v2h-.76zM87.44 13a.78.78 0 01.84-.87h.84a1.47 1.47 0 000-.21c0-.34-.14-.39-.38-.39s-1 0-1.21.07v-.52a3.25 3.25 0 011.31-.21c.62 0 1 .23 1 1v2.22h-.6l-.16-.35a1.22 1.22 0 01-.87.39.81.81 0 01-.81-.85zm1 .47a1.37 1.37 0 00.66-.24v-.59l-.69.06c-.2 0-.24.19-.24.33v.15c.02.29.15.32.29.32zM90.16 11.12l.46-.17.13-.89h.63V11H92v.64h-.64v1.28c0 .47.1.57.24.63a3.85 3.85 0 00.37.13v.51h-.66c-.43 0-.71-.29-.71-1.14v-1.46h-.46zM92.34 11h.76v2.13c0 .32.12.43.31.43a1 1 0 00.7-.28V11h.75v3.18h-.61l-.14-.34a1.46 1.46 0 01-.91.39c-.67 0-.86-.51-.86-1.11zM95.31 10c0-.09 0-.15.13-.15H96c.08 0 .12.07.12.15v.53c0 .09 0 .14-.12.14h-.57a.12.12 0 01-.13-.14zm0 1h.75v3.18h-.75zM96.41 11.12l.47-.17.12-.89h.63V11h.64v.64h-.64v1.28c0 .47.1.57.25.63a3.58 3.58 0 00.36.13v.51h-.66c-.43 0-.7-.29-.7-1.14v-1.46h-.47zM98.55 11.78c0-.49.19-.85.75-.85a4.47 4.47 0 011.53.16v.53h-1.3c-.2 0-.22.07-.22.22V12c0 .2.09.21.22.21h.68c.55 0 .76.36.76.82v.33c0 .65-.36.83-.7.83a4.81 4.81 0 01-1.6-.17v-.53H100s.19 0 .19-.2v-.15c0-.13 0-.2-.19-.2h-.67c-.5 0-.8-.24-.8-.86z"></path>
                                            </svg>
                                        </a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #fff; margin: 0; padding: 0; width: 100%; border: hidden !important;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff;  margin: 0 auto; padding: 0; width: 570px;">
                            <tr>
                                <td class="content-cell" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px; font-size: 10px; text-align: justify;">
                                    <h1 style="text-align: center;">Conditions générales de vente</h1>
                                    {!! \Illuminate\Support\Str::markdown($cgv->article) !!}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
                        <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; margin: 0 auto; padding: 0; text-align: center; width: 570px;">
                            <tr>
                                <td class="content-cell" align="center" style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px;">
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; line-height: 1.5em; margin-top: 0; font-size: 10px; text-align: center;">Euro CB SAS - Société par actions simplifiée au capital de 10 500€, 2 rue Buhan 33000 Bordeaux — RCS Bordeaux 821030574 - APE 6312Z - TVA FR28821030574<br> ©&nbsp;{{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')</p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; line-height: 1.5em; margin-top: 0; font-size: 10px; text-align: center;">
                                        <a href="tel:0800942588" style="text-decoration: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 127.84 18.72" style="height: 40px;">
                                                <path fill="#ffffff" d="M0 1.99h127.84V16.8H0z"></path>
                                                <text transform="translate(3.25 13.35)" font-size="11" fill="#7ab51d" font-family="Arial-BoldMT,Arial" font-weight="700">0 800 942 588</text>
                                                <path fill="#7ab51d" d="M76.53 1.6v3.86l3.93 3.94-3.93 3.93v3.86h47.4V1.6h-47.4z"></path>
                                                <path fill="#ffffff" d="M82.27 4.85c0-.87.46-1.12 1.07-1.13a5.34 5.34 0 011.53.17v.66c-.35 0-1.13-.06-1.32-.06s-.53 0-.53.43v.21c0 .35.12.42.43.42h.63c.77 0 .93.68.93 1.17V7c0 1-.56 1.18-1.09 1.18A4.88 4.88 0 0182.39 8v-.65c.22 0 .88.07 1.38.07.22 0 .48 0 .48-.38V6.8c0-.25-.06-.41-.35-.41h-.61c-1 0-1-.78-1-1.16zM86.64 4.93c.83 0 1.22.11 1.22 1.16a.7.7 0 01-.8.8h-.91c0 .58.19.61.6.61a6.11 6.11 0 001.06-.06V8a4.27 4.27 0 01-1.47.17c-.73 0-1-.47-1-1.64.05-1.36.39-1.6 1.3-1.6zm.22 1.36a.22.22 0 00.24-.24c0-.4 0-.47-.41-.47s-.55 0-.54.71zM88.27 5h.63l.12.4a1.18 1.18 0 01.83-.44 1 1 0 01.32 0v.81h-.42a.74.74 0 00-.73.35v2h-.75zM91.16 5l.63 2.2.64-2.2h.81l-1 3.18h-.85L90.35 5zM93.52 4a.13.13 0 01.13-.15h.57c.08 0 .12.07.12.15v.53c0 .09 0 .14-.12.14h-.57a.13.13 0 01-.13-.14zm0 1h.76v3.14h-.76zM95.69 4.93a4.17 4.17 0 011.31.19v.52h-1c-.49 0-.52 0-.52 1s.13.94.52.94 1-.06 1-.06V8a2.65 2.65 0 01-1.36.2c-.53 0-.94-.31-.94-1.62s.44-1.65.99-1.65zM98.56 4.93c.83 0 1.22.11 1.22 1.16a.7.7 0 01-.8.8h-.92c0 .58.2.61.61.61a5.94 5.94 0 001-.06V8a4.21 4.21 0 01-1.47.17c-.73 0-.94-.47-.94-1.64.05-1.36.39-1.6 1.3-1.6zm.22 1.36a.22.22 0 00.22-.24c0-.4 0-.47-.42-.47s-.54 0-.54.71zM101.88 5.93l.37-.36-.25-.45a.86.86 0 01.42-1.36 3.42 3.42 0 011.5.12v.61H103c-.41 0-.46.18-.19.59l.91 1.39.66-.69.26.36-.32.64-.2.27.74 1.09H104l-.38-.57-.35.34a1.05 1.05 0 01-1.6-.26 1.33 1.33 0 01.21-1.72zm1 1.33l.27-.29-.54-.8-.28.27a.5.5 0 00-.09.73.39.39 0 00.66.09zM106.46 7a.79.79 0 01.85-.87h.84V6c0-.34-.15-.39-.39-.39s-1 .05-1.2.07v-.57a3.22 3.22 0 011.31-.21c.61 0 1 .23 1 1v2.24h-.59l-.16-.35a1.24 1.24 0 01-.87.39.81.81 0 01-.82-.85zm1 .47a1.37 1.37 0 00.66-.24v-.56l-.7.06c-.2 0-.23.19-.23.33v.15c.03.27.16.29.3.29zM109.35 5h.65l.14.32a1.39 1.39 0 01.86-.36c.71 0 .94.66.94 1.61s-.14 1.66-.94 1.66a2.09 2.09 0 01-.9-.23v1.76h-.75zm1.43 2.57c.24 0 .37-.15.37-1s-.13-.93-.37-.93a1.09 1.09 0 00-.67.2v1.57a1.54 1.54 0 00.67.12zM112.35 5h.65l.13.32a1.45 1.45 0 01.87-.36c.7 0 .93.66.93 1.61s-.14 1.66-.93 1.66a2.1 2.1 0 01-.9-.23v1.76h-.75zm1.43 2.57c.24 0 .38-.15.38-1s-.14-.93-.38-.93a1.11 1.11 0 00-.67.2v1.57a1.57 1.57 0 00.67.12zM116.57 4.93c.83 0 1.21.11 1.21 1.16a.7.7 0 01-.8.8h-.91c0 .58.19.61.6.61a6.11 6.11 0 001.06-.06V8a4.24 4.24 0 01-1.47.17c-.73 0-1-.47-1-1.64.05-1.36.4-1.6 1.31-1.6zm.22 1.36a.22.22 0 00.24-.24c0-.4-.06-.47-.42-.47s-.55 0-.54.71zM118.19 3.45h.81v3.43c0 .46.1.56.24.62a3.85 3.85 0 00.37.13v.51h-.63c-.47 0-.74-.29-.74-1.13zM82.52 13a1.14 1.14 0 01-.31-.88c0-.76.35-1.14 1-1.14H85v.51l-.32.1a1.42 1.42 0 01.11.63.94.94 0 01-1 1.07h-.67c-.08 0-.22 0-.22.14s.1.16.22.16H84c.52 0 .85.3.85.94v.35a.82.82 0 01-.9.92h-.81a.83.83 0 01-.89-.92v-.41l.25-.29a.7.7 0 01-.28-.61.56.56 0 01.3-.57zm.79-.39h.31c.28 0 .37-.2.36-.49s-.1-.46-.37-.46h-.29c-.3 0-.36.22-.36.46s.09.45.35.45zm0 2.52h.44c.27 0 .31-.15.31-.34v-.23c0-.18-.07-.29-.28-.29H82.98v.52c.02.26.15.3.34.3zM85.27 11h.64l.12.4a1.12 1.12 0 01.82-.44 1 1 0 01.32 0v.81h-.41a.72.72 0 00-.73.35v2h-.76zM87.44 13a.78.78 0 01.84-.87h.84a1.47 1.47 0 000-.21c0-.34-.14-.39-.38-.39s-1 0-1.21.07v-.52a3.25 3.25 0 011.31-.21c.62 0 1 .23 1 1v2.22h-.6l-.16-.35a1.22 1.22 0 01-.87.39.81.81 0 01-.81-.85zm1 .47a1.37 1.37 0 00.66-.24v-.59l-.69.06c-.2 0-.24.19-.24.33v.15c.02.29.15.32.29.32zM90.16 11.12l.46-.17.13-.89h.63V11H92v.64h-.64v1.28c0 .47.1.57.24.63a3.85 3.85 0 00.37.13v.51h-.66c-.43 0-.71-.29-.71-1.14v-1.46h-.46zM92.34 11h.76v2.13c0 .32.12.43.31.43a1 1 0 00.7-.28V11h.75v3.18h-.61l-.14-.34a1.46 1.46 0 01-.91.39c-.67 0-.86-.51-.86-1.11zM95.31 10c0-.09 0-.15.13-.15H96c.08 0 .12.07.12.15v.53c0 .09 0 .14-.12.14h-.57a.12.12 0 01-.13-.14zm0 1h.75v3.18h-.75zM96.41 11.12l.47-.17.12-.89h.63V11h.64v.64h-.64v1.28c0 .47.1.57.25.63a3.58 3.58 0 00.36.13v.51h-.66c-.43 0-.7-.29-.7-1.14v-1.46h-.47zM98.55 11.78c0-.49.19-.85.75-.85a4.47 4.47 0 011.53.16v.53h-1.3c-.2 0-.22.07-.22.22V12c0 .2.09.21.22.21h.68c.55 0 .76.36.76.82v.33c0 .65-.36.83-.7.83a4.81 4.81 0 01-1.6-.17v-.53H100s.19 0 .19-.2v-.15c0-.13 0-.2-.19-.2h-.67c-.5 0-.8-.24-.8-.86z"></path>
                                            </svg>
                                        </a>
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; line-height: 1.5em; margin-top: 0; color: #000; font-size: 12px; text-align: center;">
                                        <a style="color: #000;text-decoration: none;" href="{{ route('pages.content', ['page' => 'mentions-legales']) }}">Mentions légales</a>&nbsp;|&nbsp;<a style="color: #000;text-decoration: none;" href="{{ route('pages.content', ['page' => 'cookies']) }}">Politique de confidentialité</a>&nbsp;|&nbsp;<a style="color: #000;text-decoration: none;" href="{{ route('pages.content', ['page' => 'cgv']) }}">Conditions générales de vente</a>&nbsp;|&nbsp;<a style="color: #000;text-decoration: none;" href="{{ route('pages.content', ['page' => 'cgu']) }}">Conditions générales d'utilisation</a>&nbsp;|&nbsp;<a style="color: #000;text-decoration: none;" href="{{ route('pages.acces-plus') }}">Accès+</a>&nbsp;|&nbsp;<a style="color: #000;text-decoration: none;" href="{{ route('pages.se-desabonner') }}">Désabonnement</a>&nbsp;|&nbsp;<a style="color: #000;text-decoration: none;" href="{{ route('pages.contact') }}">Contact</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
