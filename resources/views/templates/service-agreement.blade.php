<!doctype html>
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Letter Template</title>
        <style>
            header {
                position: fixed;
                top: -1.5cm;
                left: 0;
                right: 0;
                height: 1cm;
                border-bottom: 1px solid #efefef;
            }

            footer {
                position: fixed;
                bottom: -1.5cm;
                left: 0;
                right: 0;
                height: 1cm;
                border-top: 1px solid #efefef;
            }

            main {
                font-family: sans-serif;
                font-size: 12px;
            }

            main h1, main h2 {
                color: #2b4dd0;
            }

            main h2 {
                padding-top: 30px;
            }

            main a {
                color: inherit;
                text-decoration: none;
            }

            main p {
                text-align: justify;
            }

            .page-break {
                page-break-after: always;
            }
            @page {
                margin: 2.5cm 1cm 3cm 1cm !important;
                padding: 0 !important;
            }
        </style>
    </head>
    <body>
        <header>
            <table style="width: 100%; font-family: sans-serif; font-size: 12px;" cellspacing="10">
                <tr>
                    <td style="width:50%;text-align: left;">Conditions générales de vente</td>
                    <td style="width:50%;text-align: right;">{{ $activities[0]->causer->name }} — {{ $activities[0]->causer->email }} — IP : {{ $activities[0]->properties['ip'] }}</td>
                </tr>
            </table>
        </header>
        <footer>
            <table style="width: 100%; font-family: sans-serif; font-size: 12px;" cellspacing="10">
                <tr>
                    <td style="width:33%;text-align: left;">Généré par {{ config('app.name') }}</td>
                    <td style="text-align: center;">le {{ now()->format('d/m/Y H:i:s') }}</td>
                    <td style="width:33%;text-align: right;">
                        <script type="text/php">
                            if (isset($pdf)) {
                                $pdf->page_script('
                                    if ($PAGE_COUNT > 1) {
                                        $font = $fontMetrics->get_font("sans-serif", "normal");
                                        $size = 9;
                                        $pageText = "Page " . $PAGE_NUM . " sur 7";
                                        $y = 780;
                                        $x = 510;
                                        $pdf->text($x, $y, $pageText, $font, $size);
                                    }
                                ');
                            }
                        </script>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;font-size: 9px;">Euro CB SAS - Société par actions simplifiée au capital de 10 500€, 2 rue Buhan 33000 Bordeaux — RCS Bordeaux 821030574 - APE 6312Z - TVA FR28821030574</td>
                </tr>
            </table>
        </footer>
        <main>
            <h1 style="text-align: center;">{{ $page->title }}</h1>
            {!! Str::markdown($page->article) !!}
            <div class="page-break"></div>
            <h2>Signatures éléctroniques</h2>
            <table>
                <tr>
                    <td>
                        <p style="padding: 0 0 5px 0; margin: 0;">
                            <strong style="border: 1px solid #000;height: 20px; width: 20px; display: inline-block; margin-right: 5px; margin-bottom: -7px; text-align: center;">
                                <img src="{{ config('app.url') }}/css/check.png" style="height: 12px;padding-top: 5px;"/>
                            </strong> J'ai lu les conditions générales de vente et j'y adhère sans réserve
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="padding: 0; margin: 0;">
                            <strong style="border: 1px solid #000;height: 20px; width: 20px; display: inline-block;margin-right: 5px; margin-bottom: -7px; text-align: center;">
                                <img src="{{ config('app.url') }}/css/check.png" style="height: 12px;padding-top: 5px;"/>
                            </strong> Je demande expressément l'exécution de ma commande avant la fin du délai de rétraction
                        </p>
                    </td>
                </tr>
            </table>
            <h2>Audit Trail</h2>
            <table>
                @foreach($activities as $activity)
                    @if($activity->event !== 'hipayTransactionResponse' && $activity->event !== 'hipayTransactionResponse')
                        <tr>
                            <td style="padding: 20px 0 20px 0; border-bottom: 1px solid #efefef;">
                                <p style="text-align: left;padding: 0 0 5px 0; margin: 0;"><strong>{{ $activity->created_at->format('d/m/Y H:i:s') }}</strong></p>
                                <p style="text-align: left;padding: 0; margin: 0;">{{ $activity->description }} par {{ $activity->causer->name }}</p>
                                <p style="font-style: italic;color:#b0B0B0;text-align: left;padding: 0; margin: 0;">{{ $activity->causer->email }} (IP: {{ $activity->properties['ip'] }})</p>
                            </td>
                        </tr>
                    @endif
                @endforeach
                    <tr>
                        <td style="padding: 20px 0 0 0;">
                            <p style="text-align: left;padding: 0 0 5px 0; margin: 0;"><strong>{{ now()->format('d/m/Y H:i:s') }}</strong></p>
                            <p style="text-align: left;padding: 0 0 5px 0; margin: 0;"><strong>Document finalisé</strong></p>
                            <p style="text-align: left;padding: 0; margin: 0;"> ID : {{ $uuid }}</p>
                        </td>
                    </tr>
            </table>
        </main>
    </body>
</html>
