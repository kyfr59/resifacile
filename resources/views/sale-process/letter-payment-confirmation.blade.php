<x-layouts.app>
    <x-slot:head>
        <title>Merci de votre commande - {{ config('app.name') }}</title>
        <meta name="description" content="Merci de votre commande"/>
        <?php /* <meta name="robots" content="noindex, noarchive, nocache, noimageindex"/> */ ?>
        <meta name="robots" content="noindex, nofollow, noarchive, noimageindex">
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" href="{{ url()->current() }}" hreflang="fr">
    </x-slot:head>

    <div class="bg-gradient-to-r from-[#fff3ee] to-[#fff8e8] pt-16 md:pt-24">
        <div class="bg-white">
            <div class="max-w-screen-lg mx-auto py-12 md:py-24 px-6 flex flex-col-reverse md:flex-row items-center justify-center">
                <div class="w-full md:w-1/2">
                    <div class="p-1 bg-gradient-to-r from-[#FB29CD] to-[#FDC51D] rounded-xl text-lg relative">
                        <div class="bg-white h-20 w-20 absolute right-0 top-0 -mt-6 -mr-6 shadow-xl z-10 rounded-full overflow-hidden flex items-center justify-center">
                            <img src="{{ asset('images/laposte.png') }}" width="54px"/>
                        </div>
                        <div class="bg-white p-6 rounded-lg">
                            <div class="font-semibold text-gradient mt-6">
                                <div class="inline-block">
                                    <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint0_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint0_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                                </div>
                                <span class="text-gradient inline-block">Aujourd'hui</span>
                            </div>
                            <ul class="pl-6">
                                <li class="font-semibold text-base">
                                    <div class="inline-block">
                                        <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint111_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint111_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient></defs></svg>
                                    </div>
                                    Votre lettre a été envoyée par email
                                </li>
                                <li class="font-semibold text-base">
                                    <div class="inline-block">
                                        <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint111_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint111_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient></defs></svg>
                                    </div>
                                    Et votre recommandée est déjà sur le départ
                                </li>
                            </ul>
                            <div class="font-semibold text-gradient mt-6">
                                <div class="inline-block">
                                    <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint0_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint0_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                                </div>
                                <span class="text-gradient inline-block">Demain</span>
                            </div>
                            <div class="w-full flex justify-center">
                                <img src="{{ asset('images/livraison.png') }}"/>
                            </div>
                            <div class="font-semibold text-gradient mt-6">
                                <div class="inline-block">
                                    <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint0_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint0_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                                </div>
                                <span class="text-gradient inline-block">Jour 3</span>
                            </div>
                            <ul class="pl-6">
                                <li class="font-semibold text-base">
                                    <div class="inline-block">
                                        <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint111_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint111_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient></defs></svg>
                                    </div>
                                    Reception de votre lettre <span class="font-normal">par le destinataire</span>
                                </li>
                            </ul>
                            <div class="font-semibold text-gradient mt-6">
                                <div class="inline-block">
                                    <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint0_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint0_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                                </div>
                                <span class="text-gradient inline-block">Jour 4</span>
                            </div>
                            <ul class="pl-6">
                                <li class="font-semibold text-base">
                                    <div class="inline-block">
                                        <svg width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="url(#paint111_linear)"/><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#fff" stroke-width="2" stroke-linecap="round"/><defs><linearGradient id="paint111_linear" x1="0" y1="0" x2="19.288" y2="8.766" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient></defs></svg>
                                    </div>
                                    Reception de l'accusé <span class="font-normal">de réception</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 flex flex-col items-center justify-center">
                    <svg width="158" height="137" viewBox="0 0 158 137" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle opacity="0.2" cx="90.7871" cy="69.9766" r="66.5" fill="url(#paint0_linear)"/>
                            <g filter="url(#filter0_i)">
                                <circle cx="58.2871" cy="65.4766" r="54" fill="url(#paint1_linear)"/>
                            </g>
                            <path d="M85.861 59.6459L41.3844 79.2319C38.8805 80.3345 38.2501 83.5085 40.3336 85.2818C47.4361 91.3268 62.8616 101.689 78.2871 93.9766C89.9248 88.1577 91.2461 71.4936 91.1015 62.8392C91.0567 60.1582 88.3149 58.5652 85.861 59.6459Z" fill="#14142B"/>
                            <mask id="mask0" mask-type="alpha" maskUnits="userSpaceOnUse" x="39" y="59" width="53" height="38">
                                <path d="M85.861 59.6459L41.3844 79.2319C38.8805 80.3345 38.2501 83.5085 40.3336 85.2818C47.4361 91.3268 62.8616 101.689 78.2871 93.9766C89.9248 88.1577 91.2461 71.4936 91.1015 62.8392C91.0567 60.1582 88.3149 58.5652 85.861 59.6459Z" fill="#14142B"/>
                            </mask>
                            <g mask="url(#mask0)">
                                <path d="M46.8143 80.12L43.2871 74.4766L79.2871 57.4766L82.3445 63.9311C83.2165 65.7719 82.8768 67.9534 81.3424 69.2929C78.7022 71.598 74.0222 75.1847 67.7871 77.9766C61.0797 80.9799 54.5972 81.9788 51.0556 82.3111C49.335 82.4725 47.7302 81.5855 46.8143 80.12Z" fill="#FFF2DF"/>
                                <g filter="url(#filter1_i)">
                                    <ellipse cx="74.1542" cy="93.6511" rx="18" ry="11.9561" transform="rotate(-21.8428 74.1542 93.6511)" fill="#ED2E7E"/>
                                </g>
                            </g>
                            <g filter="url(#filter2_d)">
                                <g filter="url(#filter3_ii)">
                                    <path d="M40.4166 32.3818C33.2099 32.9975 30.1792 40.2709 29.5797 44.3304C24.9761 42.0515 12.3703 36.6151 9.35184 46.0586C5.32888 67.7796 23.4468 72.2441 45.2477 69.3794C57.5301 54.8022 52.8514 31.3194 40.4166 32.3818Z" fill="#FF007A"/>
                                </g>
                                <g filter="url(#filter4_f)">
                                    <ellipse cx="34.8357" cy="37.5514" rx="3.40078" ry="2.38055" transform="rotate(-29.428 34.8357 37.5514)" fill="white" fill-opacity="0.4"/>
                                </g>
                                <g filter="url(#filter5_f)">
                                    <path d="M14.9975 41.5843C17.9129 41.6335 21.6962 45.4173 23.2235 47.3031C18.2044 44.3392 12.5717 46.2795 11.3214 45.2759C10.1237 44.3147 11.3532 41.5228 14.9975 41.5843Z" fill="white" fill-opacity="0.4"/>
                                </g>
                                <ellipse cx="15.2312" cy="43.5957" rx="1.92357" ry="0.887802" transform="rotate(6.36917 15.2312 43.5957)" fill="white" fill-opacity="0.7"/>
                            </g>
                            <g filter="url(#filter6_d)">
                                <g filter="url(#filter7_ii)">
                                    <path d="M53.8832 26.5539C59.184 21.633 66.6059 24.2794 70.0141 26.5648C71.4222 21.6247 75.8727 8.63811 84.8928 12.7526C103.677 24.3777 94.801 40.7914 78.0234 55.004C58.9748 54.2959 44.7367 35.0448 53.8832 26.5539Z" fill="#FF007A"/>
                                </g>
                                <g filter="url(#filter8_f)">
                                    <ellipse rx="3.40078" ry="2.38055" transform="matrix(-0.94928 0.314432 0.314432 0.94928 62.1882 30.2222)" fill="white" fill-opacity="0.4"/>
                                </g>
                                <g filter="url(#filter9_f)">
                                    <path d="M78.5107 18.2486C76.5872 20.4398 76.8446 25.7844 77.2138 28.1828C78.394 22.4747 83.6173 19.6095 83.715 18.0092C83.8087 16.4763 80.9152 15.5095 78.5107 18.2486Z" fill="white" fill-opacity="0.4"/>
                                </g>
                                <ellipse rx="1.92357" ry="0.887802" transform="matrix(-0.996563 0.0828373 0.0828373 0.996563 62.1341 30.5553)" fill="white" fill-opacity="0.7"/>
                            </g>
                            <defs>
                                <filter id="filter0_i" x="4.28711" y="5.47656" width="113" height="114" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                    <feOffset dx="5" dy="-6"/>
                                    <feGaussianBlur stdDeviation="7"/>
                                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.21 0"/>
                                    <feBlend mode="normal" in2="shape" result="effect1_innerShadow"/>
                                </filter>
                                <filter id="filter1_i" x="52.998" y="75.8564" width="42.3123" height="39.5897" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                    <feOffset dy="4"/>
                                    <feGaussianBlur stdDeviation="3"/>
                                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.35 0"/>
                                    <feBlend mode="normal" in2="shape" result="effect1_innerShadow"/>
                                </filter>
                                <filter id="filter2_d" x="4.28711" y="31.4766" width="52.9065" height="47.9787" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
                                    <feOffset dy="4"/>
                                    <feGaussianBlur stdDeviation="1.5"/>
                                    <feColorMatrix type="matrix" values="0 0 0 0 1 0 0 0 0 0 0 0 0 0 0.478431 0 0 0 0.2 0"/>
                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
                                </filter>
                                <filter id="filter3_ii" x="7.28711" y="17.4766" width="46.9065" height="54.9787" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                    <feOffset dy="-16"/>
                                    <feGaussianBlur stdDeviation="7"/>
                                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                                    <feColorMatrix type="matrix" values="0 0 0 0 0.725 0 0 0 0 0 0 0 0 0 0.346863 0 0 0 1 0"/>
                                    <feBlend mode="normal" in2="shape" result="effect1_innerShadow"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                    <feOffset dy="-5"/>
                                    <feGaussianBlur stdDeviation="2.5"/>
                                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                                    <feBlend mode="normal" in2="effect1_innerShadow" result="effect2_innerShadow"/>
                                </filter>
                                <filter id="filter4_f" x="28.7041" y="31.8071" width="12.2633" height="11.4886" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                    <feGaussianBlur stdDeviation="1" result="effect1_foregroundBlur"/>
                                </filter>
                                <filter id="filter5_f" x="8.36035" y="37.6562" width="16.8631" height="14.5806" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                    <feGaussianBlur stdDeviation="1" result="effect1_foregroundBlur"/>
                                </filter>
                                <filter id="filter6_d" x="43.0898" y="3.93213" width="63.5925" height="64.2508" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"/>
                                    <feOffset dy="4"/>
                                    <feGaussianBlur stdDeviation="1.5"/>
                                    <feColorMatrix type="matrix" values="0 0 0 0 1 0 0 0 0 0 0 0 0 0 0.478431 0 0 0 0.2 0"/>
                                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape"/>
                                </filter>
                                <filter id="filter7_ii" x="46.0898" y="-10.0679" width="57.5925" height="71.2508" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                    <feOffset dy="-16"/>
                                    <feGaussianBlur stdDeviation="7"/>
                                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                                    <feColorMatrix type="matrix" values="0 0 0 0 0.725 0 0 0 0 0 0 0 0 0 0.346863 0 0 0 1 0"/>
                                    <feBlend mode="normal" in2="shape" result="effect1_innerShadow"/>
                                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                                    <feOffset dy="-5"/>
                                    <feGaussianBlur stdDeviation="2.5"/>
                                    <feComposite in2="hardAlpha" operator="arithmetic" k2="-1" k3="1"/>
                                    <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"/>
                                    <feBlend mode="normal" in2="effect1_innerShadow" result="effect2_innerShadow"/>
                                </filter>
                                <filter id="filter8_f" x="56.2109" y="24.8931" width="11.9536" height="10.6582" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                    <feGaussianBlur stdDeviation="1" result="effect1_foregroundBlur"/>
                                </filter>
                                <filter id="filter9_f" x="70.9521" y="13.4922" width="17.6828" height="16.6906" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                                    <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape"/>
                                    <feGaussianBlur stdDeviation="1" result="effect1_foregroundBlur"/>
                                </filter>
                                <linearGradient id="paint0_linear" x1="24.2871" y1="3.47656" x2="184.616" y2="76.3443" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#FB29CD"/>
                                    <stop offset="1" stop-color="#FDC51D"/>
                                </linearGradient>
                                <linearGradient id="paint1_linear" x1="-10.4986" y1="198.677" x2="161.305" y2="123.505" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#FB29CD"/>
                                    <stop offset="0.653321" stop-color="#FDAD1F"/>
                                    <stop offset="1" stop-color="#FDC51D"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    <h1 class="text-5xl text-center font-semibold pb-4 leading-tight text-gradient">Merci !</h1>
                    <div class="text-center font-semibold">
                        Avez-vous été satisfait ?
                    </div>
                    <a href="https://fr.trustpilot.com/evaluate/{{ config('app.name') }}" class="inline pt-6 pb-12 md:pb-0 w-1/2">
                        <img src="{{ asset('images/btn-vote.jpg') }}"/>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <x-slot:scripts>
        <script>
            @if($with_subscription)
                gtag('event', 'conversion', {
                    'send_to': 'AW-472028543/RBvXCNm1i-sBEP-qiuEB',
                    'value': '{{ \App\Helpers\Accounting::addTax($price) / 100 }}',
                    'currency': 'EUR',
                    'transaction_id': '{{ $transaction_id }}'
                });
            @endif

            window.cart360and1 = [];
            cart360and1.push({
                'intitule' : 'Lettre résilisation',
                'prixTTC' : '{{ \App\Helpers\Accounting::addTax($price) / 100 }}',
                'quantite' : '1'
            });

            @if($with_subscription)
                cart360and1.push({
                    'intitule' : 'Abonnement accès+',
                    'prixTTC' : '0',
                    'quantite' : '1'
                });
            @endif

            window.dataOrder360and1 = {
                'roiRef': '{{ $transaction_id }}',
                'prixHT':'{{ $price / 100 }}',
                'prixTTC':'{{ \App\Helpers\Accounting::addTax($price) / 100 }}',
                'fdp':'3',
                'typeV':'1',
                'cart': cart360and1
            };
        </script>
    </x-slot:scripts>
</x-layouts.app>

