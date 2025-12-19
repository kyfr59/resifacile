<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Letter Template</title>
        <style>
            @page {
                margin: 1cm 1.5cm 1cm 1.5cm !important;
                padding: 0 !important;
            }
        </style>
    </head>
    <body>
        <div style="position:relative;height:10.48689139%;width:44.44444444%">
            @include('components.address', ['person' => $sender])
        </div>
        <div style="box-sizing: border-box;position:relative;height:14.98127341%;width:45%;margin-left:49.44444444%;padding:0.5cm 0 0 1cm;">
            @include('components.address', ['person' => $recipient])
        </div>
        <div style="position:relative;width:100%;">
            <div style="box-sizing: border-box;position:relative;margin-left:49.44444444%;padding:0 0 0.2cm 1cm;">
                À {{ $data['from_city'] }}, le {{ $data['date'] }}
            </div>
            @if($data['reference'])
                <div><span style="font-weight: bold;">Réf. :</span> {{ $data['reference'] }}</div>
            @endif
            @if($data['object'])
                <div><span style="font-weight: bold;">Objet :</span> {{ $data['object'] }}</div>
            @endif
            <div style="padding: .5cm 0 .5cm 0">
                {!! $letter !!}
            </div>
            @if($data['signature'])
                <div style="box-sizing: border-box;position:relative;margin-left:49.44444444%;padding:0 0 0.5cm 1cm;">
                    <div><img src="{{ $data['signature']['image'] }}" style="width: 50%"/></div>
                </div>
            @endif
        </div>
    </body>
</html>
