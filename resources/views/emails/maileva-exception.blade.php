<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body        { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
        h2          { color: #c0392b; }
        table       { border-collapse: collapse; width: 100%; margin-bottom: 16px; }
        td, th      { border: 1px solid #ddd; padding: 8px 12px; text-align: left; }
        th          { background: #f5f5f5; width: 160px; }
        pre         { background: #f8f8f8; padding: 12px; font-size: 12px;
                      overflow-x: auto; white-space: pre-wrap; word-break: break-all; }
        .badge      { display:inline-block; padding:2px 8px; border-radius:4px;
                      font-size:12px; font-weight:bold; }
        .prod       { background:#c0392b; color:#fff; }
        .other      { background:#e67e22; color:#fff; }
        .description{ background:#fff8e1; border-left:4px solid #f39c12;
                      padding:12px 16px; margin-bottom:20px; border-radius:2px;
                      font-size:14px; line-height:1.6; white-space:pre-wrap; }
    </style>
</head>
<body>

<h2>⚠️ MailevaException – {{ $appName }}</h2>

<p>
    Environnement :
    <span class="badge {{ $env === 'production' ? 'prod' : 'other' }}">{{ strtoupper($env) }}</span>
    &nbsp;|&nbsp; <a href="{{ $appUrl }}">{{ $appUrl }}</a>
</p>

{{-- Bloc description (texte libre fourni à la levée de l'exception) --}}
@if($description)
<div class="description">{{ $description }}</div>
@endif

<table>
    <tr><th>Message</th><td>{{ $errorMessage }}</td></tr>
    @if($context)
    <tr><th>Contexte</th><td>{{ $context }}</td></tr>
    @endif
    <tr><th>Code</th><td>{{ $code }}</td></tr>
    <tr><th>Fichier</th><td>{{ $file }} : {{ $line }}</td></tr>
    <tr><th>Date</th><td>{{ now()->format('d/m/Y H:i:s') }}</td></tr>
</table>

@if(!empty($extraData))
<h3>Données supplémentaires</h3>
<table>
    @foreach($extraData as $key => $value)
    <tr>
        <th>{{ $key }}</th>
        <td>{{ is_array($value) ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $value }}</td>
    </tr>
    @endforeach
</table>
@endif

<h3>Stack trace</h3>
<pre>{{ $trace }}</pre>

</body>
</html>