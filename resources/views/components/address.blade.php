<div style="padding-bottom: .25cm">
    @if($person['compagny'])
        {{ $person['compagny'] }}
    @else
        {{ $person['first_name'] }} {{ $person['last_name'] }}
    @endif
</div>
<div>
    @if($person['address_line_2'])
        <div>{{ $person['address_line_2'] }}</div>
    @endif
    @if($person['address_line_3'])
        <div>{{ $person['address_line_3'] }}</div>
    @endif
    @if($person['address_line_4'])
        <div>{{ $person['address_line_4'] }}</div>
    @endif
    @if($person['address_line_5'])
        <div>{{ $person['address_line_5'] }}</div>
    @endif
    <div>{{ $person['postal_code'] }} {{ $person['city'] }}</div>
    <div>{{ explode('_', $person['country'])[0] }}</div>
</div>
