<div class="relative rounded-xl bg-white border-2 border-gray-100 flex mb-6 justify-center md:justify-between items-center px-12 md:px-24">
    <div class="w-1/3 md:w-auto relative flex items-center justify-center flex-col text-sm leading-tight h-16 px-6{{ ($etape !== 'remplir') ? ' text-gray-400' : '' }}">
        <div class="text-center md:text-left text-xs md:text-base font-bold">Remplir</div>
        <span class="hidden md:inline">le document</span>
        @if($etape === 'remplir')
            <div class="bg-blue-700 w-full h-1 rounded-full absolute left-0 bottom-0"></div>
        @else
            <div class="bg-blue-700 bg-opacity-25 w-full h-1 rounded-full absolute left-0 bottom-0"></div>
        @endif
    </div>
    <div class="w-1/3 md:w-auto relative flex items-center justify-center flex-col text-sm leading-tight h-16 px-6{{ ($etape !== 'formule') ? ' text-gray-400' : '' }}">
        <div class="text-center md:text-left text-xs md:text-base font-bold">Formulaires d'envoi</div>
        <span class="hidden md:inline">& options</span>
        @if($etape === 'formule')
            <div class="bg-blue-700 w-full h-1 rounded-full absolute left-0 bottom-0"></div>
        @elseif($etape === 'reglement')
            <div class="bg-blue-700 bg-opacity-25 w-full h-1 rounded-full absolute left-0 bottom-0"></div>
        @endif
    </div>
    <div class="w-1/3 md:w-auto relative flex items-center justify-center flex-col text-sm leading-tight h-16 px-6{{ ($etape !== 'reglement') ? ' text-gray-400' : '' }}">
        <div class="text-center md:text-left text-xs md:text-base font-bold">Payez</div>
        <span class="hidden md:inline">& Envoyez</span>
        @if($etape === 'reglement')
            <div class="bg-blue-700 w-full h-1 rounded-full absolute left-0 bottom-0"></div>
        @endif
    </div>
    <img class="hidden md:block absolute top-0 right-0 -mr-16 -mt-6" src="{{ asset('/images/chrono.png') }}" width="130px"/>
</div>
