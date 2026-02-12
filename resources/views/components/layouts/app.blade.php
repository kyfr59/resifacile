<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf_token" content="{{ csrf_token() }}">
        {{ $head }}
        @vite('resources/css/app.css')
        @livewireStyles
        @csrf
        @vite('resources/js/app.js')
        @if(config('app.env') === 'production')
            <script async src="https://www.googletagmanager.com/gtag/js?id=AW-472028543"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', 'UA-119402250-1');
                gtag('config', 'AW-472028543');
            </script>
            <script type="text/javascript">
                (function(c,l,a,r,i,t,y){
                    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
                })(window, document, "clarity", "script", "iw0r336ga8");
            </script>
            <script type="text/javascript">
                (function() {
                    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                    g.type='text/javascript'; g.async=true; g.defer=true;
                    g.src='https://j01l4h3n.com/optimus-rfl_5qYuT.js'; s.parentNode.insertBefore(g,s);
                })();
            </script>
        @endif
    </head>
    <body x-data="{showMenu: false}">
        <div class="sticky top-0 z-50 bg-blue-600 text-white text-center py-2 px-4">
            Site en maintenance. Disponible en consultation uniquement : l’envoi et le service de courrier sont momentanément indisponibles.
        </div>
        @if(request()->session()->has('error'))
            <div class="bg-red-50 text-red-700 p-6 text-center border-b border-red-200">
                {{ request()->session()->get('error') }}
            </div>
        @endif
        @include('components.navigations.mobile')
        <div class="flex flex-col md:flex-row items-center justify-end text-white text-xs gap-1.5 md:gap-6 leading-none py-1.5 w-full max-w-7xl mx-auto px-6 md:px-9">
            <div class="flex gap-1.5 md:gap-0 flex-row md:flex-col items-center md:items-start">
                <div class="md:text-sm"><strong>Besoin d'aide ?</strong></div>
                <div>Contactez notre service support !</div>
            </div>
            <div class="flex flex-col items-center md:items-start">
                <a href="mailto:{{ config('mail.from.address') }}" class="md:text-sm"><strong>{{ config('mail.from.address') }}</strong></a>
                <div>Du lundi au vendredi de 8h à 20h</div>
            </div>
            {{--
            <a href="tel:0800942588" class="block">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 127.84 18.72" class="h-10">
                    <path fill="#ffffff" d="M0 1.99h127.84V16.8H0z"></path>
                    <text transform="translate(3.25 13.35)" font-size="11" fill="#7ab51d" font-family="Arial-BoldMT,Arial" font-weight="700">0 800 942 588</text>
                    <path fill="#7ab51d" d="M76.53 1.6v3.86l3.93 3.94-3.93 3.93v3.86h47.4V1.6h-47.4z"></path>
                    <path fill="#ffffff" d="M82.27 4.85c0-.87.46-1.12 1.07-1.13a5.34 5.34 0 011.53.17v.66c-.35 0-1.13-.06-1.32-.06s-.53 0-.53.43v.21c0 .35.12.42.43.42h.63c.77 0 .93.68.93 1.17V7c0 1-.56 1.18-1.09 1.18A4.88 4.88 0 0182.39 8v-.65c.22 0 .88.07 1.38.07.22 0 .48 0 .48-.38V6.8c0-.25-.06-.41-.35-.41h-.61c-1 0-1-.78-1-1.16zM86.64 4.93c.83 0 1.22.11 1.22 1.16a.7.7 0 01-.8.8h-.91c0 .58.19.61.6.61a6.11 6.11 0 001.06-.06V8a4.27 4.27 0 01-1.47.17c-.73 0-1-.47-1-1.64.05-1.36.39-1.6 1.3-1.6zm.22 1.36a.22.22 0 00.24-.24c0-.4 0-.47-.41-.47s-.55 0-.54.71zM88.27 5h.63l.12.4a1.18 1.18 0 01.83-.44 1 1 0 01.32 0v.81h-.42a.74.74 0 00-.73.35v2h-.75zM91.16 5l.63 2.2.64-2.2h.81l-1 3.18h-.85L90.35 5zM93.52 4a.13.13 0 01.13-.15h.57c.08 0 .12.07.12.15v.53c0 .09 0 .14-.12.14h-.57a.13.13 0 01-.13-.14zm0 1h.76v3.14h-.76zM95.69 4.93a4.17 4.17 0 011.31.19v.52h-1c-.49 0-.52 0-.52 1s.13.94.52.94 1-.06 1-.06V8a2.65 2.65 0 01-1.36.2c-.53 0-.94-.31-.94-1.62s.44-1.65.99-1.65zM98.56 4.93c.83 0 1.22.11 1.22 1.16a.7.7 0 01-.8.8h-.92c0 .58.2.61.61.61a5.94 5.94 0 001-.06V8a4.21 4.21 0 01-1.47.17c-.73 0-.94-.47-.94-1.64.05-1.36.39-1.6 1.3-1.6zm.22 1.36a.22.22 0 00.22-.24c0-.4 0-.47-.42-.47s-.54 0-.54.71zM101.88 5.93l.37-.36-.25-.45a.86.86 0 01.42-1.36 3.42 3.42 0 011.5.12v.61H103c-.41 0-.46.18-.19.59l.91 1.39.66-.69.26.36-.32.64-.2.27.74 1.09H104l-.38-.57-.35.34a1.05 1.05 0 01-1.6-.26 1.33 1.33 0 01.21-1.72zm1 1.33l.27-.29-.54-.8-.28.27a.5.5 0 00-.09.73.39.39 0 00.66.09zM106.46 7a.79.79 0 01.85-.87h.84V6c0-.34-.15-.39-.39-.39s-1 .05-1.2.07v-.57a3.22 3.22 0 011.31-.21c.61 0 1 .23 1 1v2.24h-.59l-.16-.35a1.24 1.24 0 01-.87.39.81.81 0 01-.82-.85zm1 .47a1.37 1.37 0 00.66-.24v-.56l-.7.06c-.2 0-.23.19-.23.33v.15c.03.27.16.29.3.29zM109.35 5h.65l.14.32a1.39 1.39 0 01.86-.36c.71 0 .94.66.94 1.61s-.14 1.66-.94 1.66a2.09 2.09 0 01-.9-.23v1.76h-.75zm1.43 2.57c.24 0 .37-.15.37-1s-.13-.93-.37-.93a1.09 1.09 0 00-.67.2v1.57a1.54 1.54 0 00.67.12zM112.35 5h.65l.13.32a1.45 1.45 0 01.87-.36c.7 0 .93.66.93 1.61s-.14 1.66-.93 1.66a2.1 2.1 0 01-.9-.23v1.76h-.75zm1.43 2.57c.24 0 .38-.15.38-1s-.14-.93-.38-.93a1.11 1.11 0 00-.67.2v1.57a1.57 1.57 0 00.67.12zM116.57 4.93c.83 0 1.21.11 1.21 1.16a.7.7 0 01-.8.8h-.91c0 .58.19.61.6.61a6.11 6.11 0 001.06-.06V8a4.24 4.24 0 01-1.47.17c-.73 0-1-.47-1-1.64.05-1.36.4-1.6 1.31-1.6zm.22 1.36a.22.22 0 00.24-.24c0-.4-.06-.47-.42-.47s-.55 0-.54.71zM118.19 3.45h.81v3.43c0 .46.1.56.24.62a3.85 3.85 0 00.37.13v.51h-.63c-.47 0-.74-.29-.74-1.13zM82.52 13a1.14 1.14 0 01-.31-.88c0-.76.35-1.14 1-1.14H85v.51l-.32.1a1.42 1.42 0 01.11.63.94.94 0 01-1 1.07h-.67c-.08 0-.22 0-.22.14s.1.16.22.16H84c.52 0 .85.3.85.94v.35a.82.82 0 01-.9.92h-.81a.83.83 0 01-.89-.92v-.41l.25-.29a.7.7 0 01-.28-.61.56.56 0 01.3-.57zm.79-.39h.31c.28 0 .37-.2.36-.49s-.1-.46-.37-.46h-.29c-.3 0-.36.22-.36.46s.09.45.35.45zm0 2.52h.44c.27 0 .31-.15.31-.34v-.23c0-.18-.07-.29-.28-.29H82.98v.52c.02.26.15.3.34.3zM85.27 11h.64l.12.4a1.12 1.12 0 01.82-.44 1 1 0 01.32 0v.81h-.41a.72.72 0 00-.73.35v2h-.76zM87.44 13a.78.78 0 01.84-.87h.84a1.47 1.47 0 000-.21c0-.34-.14-.39-.38-.39s-1 0-1.21.07v-.52a3.25 3.25 0 011.31-.21c.62 0 1 .23 1 1v2.22h-.6l-.16-.35a1.22 1.22 0 01-.87.39.81.81 0 01-.81-.85zm1 .47a1.37 1.37 0 00.66-.24v-.59l-.69.06c-.2 0-.24.19-.24.33v.15c.02.29.15.32.29.32zM90.16 11.12l.46-.17.13-.89h.63V11H92v.64h-.64v1.28c0 .47.1.57.24.63a3.85 3.85 0 00.37.13v.51h-.66c-.43 0-.71-.29-.71-1.14v-1.46h-.46zM92.34 11h.76v2.13c0 .32.12.43.31.43a1 1 0 00.7-.28V11h.75v3.18h-.61l-.14-.34a1.46 1.46 0 01-.91.39c-.67 0-.86-.51-.86-1.11zM95.31 10c0-.09 0-.15.13-.15H96c.08 0 .12.07.12.15v.53c0 .09 0 .14-.12.14h-.57a.12.12 0 01-.13-.14zm0 1h.75v3.18h-.75zM96.41 11.12l.47-.17.12-.89h.63V11h.64v.64h-.64v1.28c0 .47.1.57.25.63a3.58 3.58 0 00.36.13v.51h-.66c-.43 0-.7-.29-.7-1.14v-1.46h-.47zM98.55 11.78c0-.49.19-.85.75-.85a4.47 4.47 0 011.53.16v.53h-1.3c-.2 0-.22.07-.22.22V12c0 .2.09.21.22.21h.68c.55 0 .76.36.76.82v.33c0 .65-.36.83-.7.83a4.81 4.81 0 01-1.6-.17v-.53H100s.19 0 .19-.2v-.15c0-.13 0-.2-.19-.2h-.67c-.5 0-.8-.24-.8-.86z"></path>
                </svg>
            </a>
            --}}
        </div>
        <header class="absolute w-full text-sm text-[#14142b]">
            @include('components.navigations.desktop')
        </header>
        <main>
            {{ $slot }}
        </main>
        <footer class="container mx-auto max-w-screen-xl p-6 md:p-12 text-xs">
            <div class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="https://resifacile.fr">
                        <img class="mx-auto" src="{{ asset('images/logo-footer.jpg') }}" width="225" height="71" alt="{{ config('app.name') }}">
                    </a>
                    <div class="flex flex-col items-center text-sm">
                        <div class="font-semibold">Besoin d'aide ?</div>
                        <div>Contactez notre service support</div>
                        {{--
                        <a href="tel:0800942588" class="block">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 127.84 18.72" class="h-10">
                                <path fill="#ffffff" d="M0 1.99h127.84V16.8H0z"></path>
                                <text transform="translate(3.25 13.35)" font-size="11" fill="#7ab51d" font-family="Arial-BoldMT,Arial" font-weight="700">0 800 942 588</text>
                                <path fill="#7ab51d" d="M76.53 1.6v3.86l3.93 3.94-3.93 3.93v3.86h47.4V1.6h-47.4z"></path>
                                <path fill="#ffffff" d="M82.27 4.85c0-.87.46-1.12 1.07-1.13a5.34 5.34 0 011.53.17v.66c-.35 0-1.13-.06-1.32-.06s-.53 0-.53.43v.21c0 .35.12.42.43.42h.63c.77 0 .93.68.93 1.17V7c0 1-.56 1.18-1.09 1.18A4.88 4.88 0 0182.39 8v-.65c.22 0 .88.07 1.38.07.22 0 .48 0 .48-.38V6.8c0-.25-.06-.41-.35-.41h-.61c-1 0-1-.78-1-1.16zM86.64 4.93c.83 0 1.22.11 1.22 1.16a.7.7 0 01-.8.8h-.91c0 .58.19.61.6.61a6.11 6.11 0 001.06-.06V8a4.27 4.27 0 01-1.47.17c-.73 0-1-.47-1-1.64.05-1.36.39-1.6 1.3-1.6zm.22 1.36a.22.22 0 00.24-.24c0-.4 0-.47-.41-.47s-.55 0-.54.71zM88.27 5h.63l.12.4a1.18 1.18 0 01.83-.44 1 1 0 01.32 0v.81h-.42a.74.74 0 00-.73.35v2h-.75zM91.16 5l.63 2.2.64-2.2h.81l-1 3.18h-.85L90.35 5zM93.52 4a.13.13 0 01.13-.15h.57c.08 0 .12.07.12.15v.53c0 .09 0 .14-.12.14h-.57a.13.13 0 01-.13-.14zm0 1h.76v3.14h-.76zM95.69 4.93a4.17 4.17 0 011.31.19v.52h-1c-.49 0-.52 0-.52 1s.13.94.52.94 1-.06 1-.06V8a2.65 2.65 0 01-1.36.2c-.53 0-.94-.31-.94-1.62s.44-1.65.99-1.65zM98.56 4.93c.83 0 1.22.11 1.22 1.16a.7.7 0 01-.8.8h-.92c0 .58.2.61.61.61a5.94 5.94 0 001-.06V8a4.21 4.21 0 01-1.47.17c-.73 0-.94-.47-.94-1.64.05-1.36.39-1.6 1.3-1.6zm.22 1.36a.22.22 0 00.22-.24c0-.4 0-.47-.42-.47s-.54 0-.54.71zM101.88 5.93l.37-.36-.25-.45a.86.86 0 01.42-1.36 3.42 3.42 0 011.5.12v.61H103c-.41 0-.46.18-.19.59l.91 1.39.66-.69.26.36-.32.64-.2.27.74 1.09H104l-.38-.57-.35.34a1.05 1.05 0 01-1.6-.26 1.33 1.33 0 01.21-1.72zm1 1.33l.27-.29-.54-.8-.28.27a.5.5 0 00-.09.73.39.39 0 00.66.09zM106.46 7a.79.79 0 01.85-.87h.84V6c0-.34-.15-.39-.39-.39s-1 .05-1.2.07v-.57a3.22 3.22 0 011.31-.21c.61 0 1 .23 1 1v2.24h-.59l-.16-.35a1.24 1.24 0 01-.87.39.81.81 0 01-.82-.85zm1 .47a1.37 1.37 0 00.66-.24v-.56l-.7.06c-.2 0-.23.19-.23.33v.15c.03.27.16.29.3.29zM109.35 5h.65l.14.32a1.39 1.39 0 01.86-.36c.71 0 .94.66.94 1.61s-.14 1.66-.94 1.66a2.09 2.09 0 01-.9-.23v1.76h-.75zm1.43 2.57c.24 0 .37-.15.37-1s-.13-.93-.37-.93a1.09 1.09 0 00-.67.2v1.57a1.54 1.54 0 00.67.12zM112.35 5h.65l.13.32a1.45 1.45 0 01.87-.36c.7 0 .93.66.93 1.61s-.14 1.66-.93 1.66a2.1 2.1 0 01-.9-.23v1.76h-.75zm1.43 2.57c.24 0 .38-.15.38-1s-.14-.93-.38-.93a1.11 1.11 0 00-.67.2v1.57a1.57 1.57 0 00.67.12zM116.57 4.93c.83 0 1.21.11 1.21 1.16a.7.7 0 01-.8.8h-.91c0 .58.19.61.6.61a6.11 6.11 0 001.06-.06V8a4.24 4.24 0 01-1.47.17c-.73 0-1-.47-1-1.64.05-1.36.4-1.6 1.31-1.6zm.22 1.36a.22.22 0 00.24-.24c0-.4-.06-.47-.42-.47s-.55 0-.54.71zM118.19 3.45h.81v3.43c0 .46.1.56.24.62a3.85 3.85 0 00.37.13v.51h-.63c-.47 0-.74-.29-.74-1.13zM82.52 13a1.14 1.14 0 01-.31-.88c0-.76.35-1.14 1-1.14H85v.51l-.32.1a1.42 1.42 0 01.11.63.94.94 0 01-1 1.07h-.67c-.08 0-.22 0-.22.14s.1.16.22.16H84c.52 0 .85.3.85.94v.35a.82.82 0 01-.9.92h-.81a.83.83 0 01-.89-.92v-.41l.25-.29a.7.7 0 01-.28-.61.56.56 0 01.3-.57zm.79-.39h.31c.28 0 .37-.2.36-.49s-.1-.46-.37-.46h-.29c-.3 0-.36.22-.36.46s.09.45.35.45zm0 2.52h.44c.27 0 .31-.15.31-.34v-.23c0-.18-.07-.29-.28-.29H82.98v.52c.02.26.15.3.34.3zM85.27 11h.64l.12.4a1.12 1.12 0 01.82-.44 1 1 0 01.32 0v.81h-.41a.72.72 0 00-.73.35v2h-.76zM87.44 13a.78.78 0 01.84-.87h.84a1.47 1.47 0 000-.21c0-.34-.14-.39-.38-.39s-1 0-1.21.07v-.52a3.25 3.25 0 011.31-.21c.62 0 1 .23 1 1v2.22h-.6l-.16-.35a1.22 1.22 0 01-.87.39.81.81 0 01-.81-.85zm1 .47a1.37 1.37 0 00.66-.24v-.59l-.69.06c-.2 0-.24.19-.24.33v.15c.02.29.15.32.29.32zM90.16 11.12l.46-.17.13-.89h.63V11H92v.64h-.64v1.28c0 .47.1.57.24.63a3.85 3.85 0 00.37.13v.51h-.66c-.43 0-.71-.29-.71-1.14v-1.46h-.46zM92.34 11h.76v2.13c0 .32.12.43.31.43a1 1 0 00.7-.28V11h.75v3.18h-.61l-.14-.34a1.46 1.46 0 01-.91.39c-.67 0-.86-.51-.86-1.11zM95.31 10c0-.09 0-.15.13-.15H96c.08 0 .12.07.12.15v.53c0 .09 0 .14-.12.14h-.57a.12.12 0 01-.13-.14zm0 1h.75v3.18h-.75zM96.41 11.12l.47-.17.12-.89h.63V11h.64v.64h-.64v1.28c0 .47.1.57.25.63a3.58 3.58 0 00.36.13v.51h-.66c-.43 0-.7-.29-.7-1.14v-1.46h-.47zM98.55 11.78c0-.49.19-.85.75-.85a4.47 4.47 0 011.53.16v.53h-1.3c-.2 0-.22.07-.22.22V12c0 .2.09.21.22.21h.68c.55 0 .76.36.76.82v.33c0 .65-.36.83-.7.83a4.81 4.81 0 01-1.6-.17v-.53H100s.19 0 .19-.2v-.15c0-.13 0-.2-.19-.2h-.67c-.5 0-.8-.24-.8-.86z"></path>
                            </svg>
                        </a>
                        --}}
                        <div class="text-xs">Du lundi au vendredi de 8h à 20h</div>
                    </div>
                    <div class="flex flex-col items-center md:items-end">
                        <div>Paiments sécurisés</div>
                        <div class="flex gap-3 items-center justify-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 -ml-2" viewBox="0 0 152.407 108"><path d="M60.412 25.697h31.5v56.606h-31.5z" style="fill:#ff5f00"></path><path d="M382.208 306a35.938 35.938 0 0 1 13.75-28.303 36 36 0 1 0 0 56.606A35.938 35.938 0 0 1 382.208 306Z" style="fill:#eb001b" transform="translate(-319.796 -252)"></path><path d="M454.203 306a35.999 35.999 0 0 1-58.245 28.303 36.005 36.005 0 0 0 0-56.606A35.999 35.999 0 0 1 454.203 306ZM450.769 328.308v-1.16h.467v-.235h-1.19v.236h.468v1.159Zm2.31 0v-1.398h-.364l-.42.962-.42-.962h-.365v1.398h.258v-1.054l.393.908h.267l.394-.91v1.056Z" style="fill:#f79e1b" transform="translate(-319.796 -252)"></path></svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1920.01 620.08" class="h-6 -ml-3"><path d="M729 10.96 477.63 610.7h-164l-123.7-478.62c-7.51-29.48-14.04-40.28-36.88-52.7C115.76 59.15 54.18 40.17 0 28.39l3.68-17.43h263.99c33.65 0 63.9 22.4 71.54 61.15l65.33 347.04L566 10.95h163Zm642.58 403.93c.66-158.29-218.88-167.01-217.37-237.72.47-21.52 20.96-44.4 65.81-50.24 22.23-2.91 83.48-5.13 152.95 26.84l27.25-127.18C1362.89 13.04 1314.86 0 1255.1 0c-153.35 0-261.27 81.52-262.18 198.25-.99 86.34 77.03 134.52 135.81 163.21 60.47 29.38 80.76 48.26 80.53 74.54-.43 40.23-48.23 57.99-92.9 58.69-77.98 1.2-123.23-21.1-159.3-37.87l-28.12 131.39c36.25 16.63 103.16 31.14 172.53 31.87 162.99 0 269.61-80.51 270.11-205.19m404.94 195.81h143.49L1794.76 10.96h-132.44c-29.78 0-54.9 17.34-66.02 44L1363.49 610.7h162.91l32.34-89.58h199.05l18.73 89.58Zm-173.11-212.5 81.66-225.18 47 225.18h-128.66ZM950.67 10.96 822.38 610.7H667.24L795.58 10.96h155.09Z" data-name="Layer 1" style="fill:#1434cb"></path></svg>
                        </div>
                    </div>
                </div>
                <nav class="flex flex-col md:flex-row justify-center items-center divide-y md:divide-y-0 md:divide-x divide-gray-600">
                    <a class="w-full md:w-auto text-center md:text-left py-3 md:py-0 px-2" href="{{ route('pages.content', ['page' => 'cgv']) }}">Conditions générales de vente</a>
                    <a class="w-full md:w-auto text-center md:text-left py-3 md:py-0 px-2" href="{{ route('pages.content', ['page' => 'envoi-lettre-recommandee']) }}">Envoi de lettre recommandée</a>
                    <a class="w-full md:w-auto text-center md:text-left py-3 md:py-0 px-2" href="{{ route('pages.content', ['page' => 'suivi-courrier']) }}">Suivi de lettre</a>
                    <a class="w-full md:w-auto text-center md:text-left py-3 md:py-0 px-2" href="{{ route('pages.content', ['page' => 'mentions-legales']) }}">Mentions légales</a>
                    <a class="w-full md:w-auto text-center md:text-left py-3 md:py-0 px-2" href="{{ route('pages.contact') }}">Contact</a>
                    <a class="w-full md:w-auto text-center md:text-left py-3 md:py-0 px-2" href="{{ route('pages.se-desabonner') }}">Se désabonner</a>
                    <a class="w-full md:w-auto text-center md:text-left py-3 md:py-0 px-2" href="{{ route('pages.plan-site') }}">Plan du site</a>
                </nav>
                <div class="flex justify-center items-center text-gray-400">{{ config('app.name') . ' - ©' . now()->format('Y') . ' tous droits réservés' }}</div>
            </div>
        </footer>
        @livewireScriptConfig
        @routes
        @stack('scripts')
        {{ $scripts ?? '' }}
    </body>
</html>
