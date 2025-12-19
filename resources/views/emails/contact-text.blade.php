first_name : {{ $contact->first_name }}
last_name : {{ $contact->last_name }}
email : {{ $contact->email }}
@if($contact->phone)
phone : {{ $contact->phone }}
@endif
———
{{ $contact->message }}
