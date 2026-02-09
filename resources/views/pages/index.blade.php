<?php

use Illuminate\Mail\Markdown;
use function Laravel\Folio\{name};
name('pages.index');

$page = \App\Models\Page::find(6);
$categories = \App\Models\Category::all();
$models = \App\Models\Template::orderByDesc('views')->limit(12)->get();
$guides = \App\Models\Guide::orderByDesc('created_at')->limit(3)->get();

?>

<x-layouts.app>
    <x-slot:head>
        <title>{{ $page->seo_title }} - {{ config('app.name') }}</title>
        <meta name="description" content="{{ $page->seo_description }}"/>
        <meta name="robots" content="index, noarchive, nocache, imageindex"/>
        <link rel="canonical" href="{{ url()->current() }}/">
        <link rel="alternate" href="{{ url()->current() }}/" hreflang="fr">
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "Organization",
                "name": "EuroCB",
                "areaServed" : ["FR"],
                "url": "https://eurocb.net",
                "logo": "https://eurocb.net/img/logo.png",
                "brand":  {
                    "@type": "service",
                    "name": "Resifacile.fr"
                },
                "contactPoint": {
                    "@type": "ContactPoint",
                    "email": "contact@resifacile.fr",
                    "url": "{{ url()->current() }}",
                    "telephone": "0 800 942 588",
                    "areaServed" : ["FR"],
                    "availableLanguage" : ["French"],
                    "hoursAvailable": [{
                        "@type": "OpeningHoursSpecification",
                        "dayOfWeek": "http://schema.org/Monday",
                        "opens":  "08:00:00",
                        "closes":  "20:00:00"
                    },
                    {
                        "@type": "OpeningHoursSpecification",
                        "dayOfWeek": "http://schema.org/Tuesday",
                        "opens":  "08:00:00",
                        "closes":  "20:00:00"
                    },
                    {
                        "@type": "OpeningHoursSpecification",
                        "dayOfWeek": "http://schema.org/Wednesday",
                        "opens":  "08:00:00",
                        "closes":  "20:00:00"
                    },
                    {
                        "@type": "OpeningHoursSpecification",
                        "dayOfWeek": "http://schema.org/Thursday",
                        "opens":  "08:00:00",
                        "closes":  "20:00:00"
                    },
                    {
                        "@type": "OpeningHoursSpecification",
                        "dayOfWeek": "http://schema.org/Friday",
                        "opens":  "08:00:00",
                        "closes":  "20:00:00"
                    }]
                }
            }
        </script>
        <script type="application/ld+json">
            {
                "@context": "http://schema.org",
                "@type": "WebSite",
                "name": "{{ url()->current() }}/"
            }
        </script>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "FAQPage",
                "mainEntity": [{
                    "@type": "Question",
                    "name": "Qu’est-ce qu’une lettre de résiliation ?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "<p>La résiliation signifie la résiliation anticipée d’un contrat. Cette solution est offerte aux consommateurs ou aux prestataires afin de pouvoir rompre un contrat avant son terme. Les trois formes de résiliation qui existent sont les suivantes :</p><ul><li>Une résiliation amiable.</li><li>Une résiliation de plein droit</li><li>Une résiliation judiciaire</li></ul><p>Les dispositifs de résiliation sont fixés par la loi française afin de respecter les droits du consommateur. La différence fondamentale entre la résolution et la résiliation est que la résiliation n’annule aucunement les effets déjà exécutés sur toute la durée du contrat contracté. Une lettre de résiliation est donc l’outil principal pour résilier un abonnement de son plein droit. Resifacile.fr, <strong>des modèles de lettre de résiliation en ligne</strong> sont à votre disposition afin de vous accompagner dans tout le processus de résiliation. Une fois que vous aurez choisi votre modèle de lettre type nous nous occupons de l’envoi en lettre recommandée avec accusé de réception pour plus de sécurité.</p>"
                    }
                },{
                    "@type": "Question",
                    "name": "Qu’est-ce qu’une lettre en recommandé ?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "<p><strong>Envoyer une lettre de résiliation</strong> en recommandé avec accusé de réception permet de garder une preuve de l’expédition du courrier à l’entreprise désignée, à une date précise.</p><p>Dans certains cas la loi impose d’envoyer un courrier en AR, par exemple, dans le cadre d’une location ou d’un licenciement. Ce mode d’envoi peut également être utilisé comme dernière tentative de règlement à l’amiable. </p><p>Une lettre recommandée avec accusé de réception est donc un moyen sûr pour envoyer un courrier de résiliation afin de mettre un terme à un contrat.</p>"
                    }
                },{
                    "@type": "Question",
                    "name": "Qu’est-ce que la résiliation en ligne ?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "<p>Après avoir abordé les principes fondamentaux d’une lettre de résiliation classique, découvrez les <strong>avantages de la résiliation en ligne</strong>. </p><h3>Résilier en ligne permet de gagner du temps</h3><p>Résilier en ligne est un nouveau moyen de mettre un terme à un abonnement rapidement et en toute sérénité ! En effet, il est aujourd’hui possible de gérer certaines de vos démarches administratives directement en ligne. Ce type de procédé permet d’envoyer un courrier de résiliation grâce à nos différents <strong>modèles de lettres de résiliation en ligne</strong>. </p><h3>L’utilisation d’un modèle de lettre de résiliation permet d’éviter les erreurs</h3><p>Plus simple et plus sécurisé, vous pouvez directement sélectionner la lettre de résiliation adaptée à vos besoins et en direction de l’entité destinataire. Nous nous chargeons de trouver l’adresse de la société ainsi que le contenu clé en main. Votre courrier est alors prêt à partir grâce à notre partenariat avec un grand groupe postal. </p><h3>La résiliation de votre contrat se fait en toute simplicité </h3><p>Grâce à notre formulaire en ligne rapide et efficace, la démarche de résiliation est facilitée. En quelques clics, envoyez votre courrier de résiliation à Bouygues, <a href=https://www.resifacile.fr/marques/sfr>SFR</a> ou encore à votre club de sport tel que <a href=https://www.basic-fit.com/fr-fr/resilier>Basic Fit</a>.</p>"
                    }
                },{
                    "@type": "Question",
                    "name": "Comment résilier en ligne avec resifacile.fr ?",
                    "acceptedAnswer": {
                        "@type": "Answer",
                        "text": "<p>Les équipes de resifacile.fr sont à votre écoute pour vous accompagner étape par étape afin que vous puissiez <strong>résilier un abonnement</strong> ou un contrat en toute sécurité. Il est impératif que votre demande de résiliation soit acheminée dans les plus brefs délais. </p><h3>Trouver le modèle de lettre de résiliation qui vous correspond</h3><p>Tout d’abord, il est important que vous puissiez trouver le correspondant à qui vous souhaitez envoyer le courrier de résiliation. Vous pouvez : </p><ul><li>Utiliser un de nos modèles de lettres de résiliation par thématique et intégrer vous-même les coordonnées du destinataire.</li><li>Sélectionner l’entreprise destinataire de votre lettre de résiliation. Dans ce cas précis, l’adresse de destination est préremplie.</li></ul><h3>Remplissez le formulaire permettant de remplir votre lettre de résiliation </h3><p>Une fois cette première étape effectuée, il vous suffit de remplir le formulaire dans lequel vous mentionnez par exemple votre numéro de contrat et vos coordonnées permettant au destinataire de vous identifier.</p><h3>Vérifiez la lettre de résiliation à envoyer </h3><p>Après avoir rempli l’ensemble du formulaire, nous vous invitons à vérifier la <strong>lettre de résiliation</strong> finale et de procéder au paiement</p><h3>Payez en toute simplicité</h3><p>Profitez de notre offre « <a href=https://resifacile.fr/acces-plus>Accès+</a> » vous permettant de bénéficier de nombreux avantages et payez en toute simplicité.</p><h3>Attendez votre numéro de suivi envoyé par email</h3><p>Vous réceptionnez une confirmation de commande à la suite de votre achat du modèle de résiliation sélectionné et après quelques jours, un email vous est envoyé comprenant le numéro de suivi de votre <strong>lettre de résiliation</strong>. </p><h2>Comment suivre les lettres recommandées ?</h2><h3>Votre lettre de résiliation est prise en charge par nos services</h3><p>Après avoir utilisé nos services, vous réceptionnez un email dans lequel vous trouverez votre <a href=https://resifacile.fr/suivi-courrier>numéro de suivi</a>. Ce dernier vous permettra de connaître l’avancée de votre courrier.</p><h3>Un email avec un numéro de suivi de votre courrier de résiliation est envoyé par email</h3><p>Si vous rencontrez quelconque problème lié à votre numéro de suivi, n’hésitez pas à contacter notre service client disponible de 8h à 20h par téléphone ou par email à contact@resifacile.fr. </p>"
                    }
                }]
            }
        </script>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement": [{
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Accueil",
                    "item": "{{ url()->current() }}"
                }]
            }
        </script>
    </x-slot:head>

    <div class="bg-none md:bg-[url('/images/accueil.jpg')] md:bg-right-top md:bg-[length:500px_501px] md:bg-no-repeat">
        <livewire:jumbo-search-brand/>
        <div class="bg-gray-50">
            <div class="container mx-auto max-w-screen-lg py-12 md:py-24 px-6">
                <h2 class="text-3xl md:text-4xl text-blue-700 font-semibold">Deux options pour résilier</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-12 mt-6">
                    <div class="relative bg-white shadow-xl rounded-xl p-6 overflow-hidden">
                        <div class="text-2xl md:text-3xl font-semibold pb-2 relative z-[2]">Lettre recommandé</div>
                        <div class="font-semibold relative z-[2]">Lettre de résiliation + envoi</div>
                        <ul class="relative z-[2]">
                            <li>Distribution J+3</li>
                            <li>Preuve de dépôt</li>
                            <li>Avec avis de réception</li>
                        </ul>
                        <div class="flex justify-end w-full pt-6 relative z-[2]">
                            <div class="font-semibold text-6xl">5,22<span class="font-light">€<sup>*</sup></span></div>
                        </div>
                        <svg width="109" height="90" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute bottom-0 left-0 z-0"><path d="M24.45 20.674a6 6 0 015.968.623l43.298 31.4a6 6 0 012.446 5.48l-5.545 53.197a5.999 5.999 0 01-3.523 4.857l-48.842 21.797a5.998 5.998 0 01-5.968-.622l-43.298-31.4a6.002 6.002 0 01-2.445-5.48l5.544-53.197a6 6 0 013.523-4.857L24.45 20.674z" fill="url(#visu1)"></path><path d="M107.912 38.846l.075-.03c.021-.008.043-.015.064-.025.024-.011.048-.024.071-.036.021-.011.043-.021.063-.034l.065-.041c.021-.014.042-.027.062-.042.021-.015.04-.031.059-.047.021-.017.041-.032.06-.05.021-.018.04-.038.06-.058.017-.017.034-.032.05-.05.03-.033.058-.067.085-.102l.015-.018.014-.021a1.54 1.54 0 00.074-.11c.014-.022.025-.044.038-.067l.038-.07.034-.079c.01-.022.02-.044.028-.067.011-.026.018-.053.027-.08l.023-.071c.007-.026.012-.052.018-.079l.017-.077c.005-.026.008-.053.011-.08.003-.026.008-.052.01-.078.003-.031.003-.063.004-.094l.002-.066c0-.055-.003-.11-.008-.163L105.579 2.32c-.087-.9-.834-1.552-1.668-1.457L56.354 6.252c-.014.001-.026.005-.04.006a1.43 1.43 0 00-.86.45 1.963 1.963 0 00-.152.186l-.025.035-.013.022a1.662 1.662 0 00-.047.08c-.011.019-.023.039-.033.059a1.726 1.726 0 00-.038.08l-.029.065-.028.078c-.008.024-.017.048-.024.073l-.02.078-.017.076-.013.078-.011.08a2.072 2.072 0 00-.01.162l.002.072c.001.03.002.061.005.092l.001.026.979 10.038c.088.9.834 1.551 1.668 1.457.834-.095 1.44-.9 1.352-1.799l-.627-6.423 14.672 11.292a1.615 1.615 0 00-.183.202L61.032 38.57l-1.043-10.697c-.088-.899-.835-1.55-1.669-1.456-.834.094-1.439.9-1.35 1.798l.193 1.99-7.8.883c-.834.095-1.44.9-1.351 1.799.087.9.834 1.551 1.668 1.456l7.8-.883.915 9.382c.088.9.834 1.551 1.669 1.457l47.557-5.39c.049-.005.099-.013.148-.024.023-.005.045-.013.067-.019l.077-.021zm-2.344-5.321L90.956 20.767l-.029-.023 9.054-10.219c.579-.653.557-1.69-.049-2.314a1.442 1.442 0 00-2.146.054L82.314 25.726l-21.66-16.67 42.064-4.766 2.85 29.235zm-30.345-8.648a1.66 1.66 0 00.226-.413l6.172 4.75c.313.24.683.337 1.041.296.348-.04.682-.207.94-.497l5.24-5.916c.061.077.128.149.203.214l14.612 12.758-40.267 4.563 11.833-15.755z" fill="#000"></path><path d="M39.12 26.947l25.499-2.89c.834-.094 1.439-.9 1.351-1.798-.088-.9-.834-1.551-1.668-1.457l-25.499 2.89c-.834.094-1.438.899-1.35 1.798.087.9.834 1.551 1.668 1.457zM40.889 17.677l11.449-1.297c.834-.094 1.438-.9 1.35-1.799-.087-.899-.834-1.55-1.668-1.456l-11.449 1.297c-.834.095-1.438.9-1.35 1.799.087.899.834 1.551 1.668 1.456zM51.374 37.81l-11.072 1.254c-.834.095-1.439.9-1.35 1.8.087.898.834 1.55 1.668 1.456l11.071-1.255c.834-.094 1.439-.9 1.351-1.799-.088-.899-.834-1.55-1.668-1.456z" fill="#000"></path><defs><linearGradient id="visu1" x1="73.366" y1="33.82" x2="-44.141" y2="172.145" gradientUnits="userSpaceOnUse"><stop stop-color="#FFC700"></stop><stop offset="1" stop-color="#FF52E5"></stop></linearGradient></defs></svg>
                    </div>
                    <div class="relative bg-white shadow-xl rounded-xl p-6 overflow-hidden">
                        <div class="text-2xl md:text-3xl font-semibold pb-2 relative z-[2]">Lettre suivie</div>
                        <div class="font-semibold relative z-[2]">Lettre de résiliation + envoi</div>
                        <ul class="relative z-[2]">
                            <li>Distribution J+3</li>
                            <li>Suivi de votre courrier</li>
                            <li>&nbsp;</li>
                        </ul>
                        <div class="flex justify-end w-full pt-6 relative z-[2]">
                            <div class="font-semibold text-6xl">2,22<span class="font-light">€<sup>*</sup></span></div>
                        </div>
                        <svg width="109" height="90" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute bottom-0 left-0 z-0"><path d="M24.45 20.674a6 6 0 015.968.623l43.298 31.4a6 6 0 012.446 5.48l-5.545 53.197a5.999 5.999 0 01-3.523 4.857l-48.842 21.797a5.998 5.998 0 01-5.968-.622l-43.298-31.4a6.002 6.002 0 01-2.445-5.48l5.544-53.197a6 6 0 013.523-4.857L24.45 20.674z" fill="url(#visu2)"></path><path d="M107.912 38.846l.075-.03c.021-.008.043-.015.064-.025.024-.011.048-.024.071-.036.021-.011.043-.021.063-.034l.065-.041c.021-.014.042-.027.062-.042.021-.015.04-.031.059-.047.021-.017.041-.032.06-.05.021-.018.04-.038.06-.058.017-.017.034-.032.05-.05.03-.033.058-.067.085-.102l.015-.018.014-.021a1.54 1.54 0 00.074-.11c.014-.022.025-.044.038-.067l.038-.07.034-.079c.01-.022.02-.044.028-.067.011-.026.018-.053.027-.08l.023-.071c.007-.026.012-.052.018-.079l.017-.077c.005-.026.008-.053.011-.08.003-.026.008-.052.01-.078.003-.031.003-.063.004-.094l.002-.066c0-.055-.003-.11-.008-.163L105.579 2.32c-.087-.9-.834-1.552-1.668-1.457L56.354 6.252c-.014.001-.026.005-.04.006a1.43 1.43 0 00-.86.45 1.963 1.963 0 00-.152.186l-.025.035-.013.022a1.662 1.662 0 00-.047.08c-.011.019-.023.039-.033.059a1.726 1.726 0 00-.038.08l-.029.065-.028.078c-.008.024-.017.048-.024.073l-.02.078-.017.076-.013.078-.011.08a2.072 2.072 0 00-.01.162l.002.072c.001.03.002.061.005.092l.001.026.979 10.038c.088.9.834 1.551 1.668 1.457.834-.095 1.44-.9 1.352-1.799l-.627-6.423 14.672 11.292a1.615 1.615 0 00-.183.202L61.032 38.57l-1.043-10.697c-.088-.899-.835-1.55-1.669-1.456-.834.094-1.439.9-1.35 1.798l.193 1.99-7.8.883c-.834.095-1.44.9-1.351 1.799.087.9.834 1.551 1.668 1.456l7.8-.883.915 9.382c.088.9.834 1.551 1.669 1.457l47.557-5.39c.049-.005.099-.013.148-.024.023-.005.045-.013.067-.019l.077-.021zm-2.344-5.321L90.956 20.767l-.029-.023 9.054-10.219c.579-.653.557-1.69-.049-2.314a1.442 1.442 0 00-2.146.054L82.314 25.726l-21.66-16.67 42.064-4.766 2.85 29.235zm-30.345-8.648a1.66 1.66 0 00.226-.413l6.172 4.75c.313.24.683.337 1.041.296.348-.04.682-.207.94-.497l5.24-5.916c.061.077.128.149.203.214l14.612 12.758-40.267 4.563 11.833-15.755z" fill="#000"></path><path d="M39.12 26.947l25.499-2.89c.834-.094 1.439-.9 1.351-1.798-.088-.9-.834-1.551-1.668-1.457l-25.499 2.89c-.834.094-1.438.899-1.35 1.798.087.9.834 1.551 1.668 1.457zM40.889 17.677l11.449-1.297c.834-.094 1.438-.9 1.35-1.799-.087-.899-.834-1.55-1.668-1.456l-11.449 1.297c-.834.095-1.438.9-1.35 1.799.087.899.834 1.551 1.668 1.456zM51.374 37.81l-11.072 1.254c-.834.095-1.439.9-1.35 1.8.087.898.834 1.55 1.668 1.456l11.071-1.255c.834-.094 1.439-.9 1.351-1.799-.088-.899-.834-1.55-1.668-1.456z" fill="#000"></path><defs><linearGradient id="visu2" x1="73.366" y1="33.82" x2="-44.141" y2="172.145" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"></stop><stop offset="1" stop-color="#06F0FF"></stop></linearGradient></defs></svg>
                    </div>
                </div>
                <div class="flex flex-col items-center justify-center gap-6 md:gap-12">
                    <a href="{{ route('pages.se-desabonner') }}" class="w-full md:w-auto mt-12 h-12 px-12 border-2 border-blue-700 text-blue-700 rounded-xl text-base md:text-sm inline-flex items-center justify-center">
                        Résilier mon abonnement
                    </a>
                    <div class="text-[11px] text-justify text-gray-600">
                        *L’offre « accès+ » vous permet d'envoyer vos courriers depuis le site {{ config('app.name') }} en bénéficiant de prix réduit sur tout vos envois (-50%), l'accès à plus de 500 modèles de courrier et l'archivage de vos commandes. Cette offre tarifaire dite « accès+ » est valable uniquement dans le cas de la souscription d’un abonnement sans engagement, dont les quinze (15) premiers jours sont offerts, puis facturé à raison de trente-neuf euros et quatre-vingt-dix centimes (39,90€) tous les mois, conformément à nos conditions générales de vente, et résiliable à tout moment. Dans le cas où vous ne souhaiteriez pas vous abonner à notre service dit « accès+ », vous pouvez vous reporter sur nos offres unitaires. Le délais de rétractation expire quatorze (14) jours après le jour de la conclusion du contrat abonnement « accès+ ». Durant la période de quatorze (14) jours après l’exécution du contrat, vous disposez de votre droit de rétractation. Pour procéder à votre droit de rétractation, vous avez la possibilité de procéder à la résiliation à cette adresse :
                        {{ route('pages.se-desabonner') }}.
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-[#14142b]">
            <div class="max-w-screen-lg mx-auto py-12 md:py-24 px-6 text-white">
                <div class="w-full text-4xl md:text-5xl leading-tight font-semibold">Oubliez la paperasse, <span class="text-gradient inline-block">les files d'attente et le stress</span></div>
                <div class="flex flex-wrap justify-between items-center py-12">
                    <div class="w-full md:w-1/3 pb-6 md:pb-0">
                        <img src="{{ asset('/images/arg-1.gif') }}" alt="Une moyenne de 45 min de gagnée">
                    </div>
                    <div class="w-full md:w-2/3 md:pl-24">
                        <div class="text-xl md:text-2xl text-gray-400 font-semibold">Rapide & efficace</div>
                        <div class="text-2xl md:text-3xl font-semibold pb-3 md:pb-0">Un formulaire et c'est plié</div>
                        <div class="text-blue-500 font-semibold">2 minutes et c'est tout !</div>
                        <div class="text-lg">Plus besoin de vous déplacer, ou de chercher un créneau entre toutes vos activités pour vous occuper de vos recommandés</div>
                        <div class="flex pt-3 gap-6 md:gap-12 font-semibold">
                            <div class="w-1/2">
                                <svg width="45" height="42" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="14" cy="28" r="13" stroke="url(#paint0_linear)" stroke-width="2"/><g clip-path="url(#clip0)" fill="#FCFCFC"><path d="M43.012 27.58c.013-.003.026-.008.038-.013l.033-.012.037-.018.032-.016.034-.02.032-.02.03-.022.031-.023.03-.028.026-.024a.778.778 0 00.044-.049l.008-.009.007-.01a.781.781 0 00.038-.052l.02-.032.019-.034.018-.037c.005-.01.01-.02.014-.032l.014-.038.012-.034.01-.037.008-.037.005-.038.005-.038.003-.045v-.031a.75.75 0 00-.004-.078l-1.744-16.596a.78.78 0 00-.858-.695l-24.458 2.57-.02.004a.719.719 0 00-.224.064.746.746 0 00-.245.178.955.955 0 00-.052.061l-.013.017-.006.01a.739.739 0 00-.025.038l-.016.028a.797.797 0 00-.02.038l-.015.032-.014.037c-.004.012-.009.023-.012.035-.004.012-.008.024-.01.037l-.01.036-.006.037-.006.039-.003.036a.945.945 0 00-.002.04l.001.035.002.044.001.013.504 4.788a.78.78 0 101.552-.163l-.322-3.064 7.546 5.386a.787.787 0 00-.094.097L18.9 27.45l-.536-5.103a.78.78 0 10-1.553.163l.1.949-4.012.422a.78.78 0 10.163 1.552l4.012-.421.47 4.475a.78.78 0 00.858.695l24.459-2.57a.788.788 0 00.076-.012l.034-.009.04-.01zm-1.205-2.538l-7.516-6.085-.014-.01 4.656-4.876a.78.78 0 10-1.129-1.078l-7.957 8.33-11.14-7.953 21.634-2.273 1.466 13.945zM26.2 20.917a.779.779 0 00.116-.197l3.174 2.266a.78.78 0 001.018-.096l2.696-2.822c.03.037.065.071.104.102l7.515 6.086-20.709 2.177 6.086-7.515z"/><path d="M7.634 21.905l13.113-1.379a.78.78 0 10-.163-1.552L7.47 20.352a.78.78 0 10.164 1.553zM8.543 17.483l5.888-.619a.78.78 0 10-.163-1.553l-5.889.62a.78.78 0 10.164 1.552zM13.935 27.087l-5.694.598a.78.78 0 10.163 1.553l5.694-.599a.78.78 0 10-.163-1.552z"/></g><defs><linearGradient id="paint0_linear" x1="-3.833" y1="62.533" x2="40.708" y2="43.044" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset=".653" stop-color="#FDAD1F"/><stop offset="1" stop-color="#FDC51D"/></linearGradient><clipPath id="clip0"><path fill="#fff" transform="rotate(-6 43.686 -45.544)" d="M0 0h36v36H0z"/></clipPath></defs></svg>
                                2 min de formulaire au lieu d’1 heure
                            </div>
                            <div class="w-1/2">
                                <svg width="45" height="42" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="14" cy="28" r="13" stroke="url(#paint0_linear)" stroke-width="2"/><g clip-path="url(#clip0)" fill="#FCFCFC"><path d="M43.012 27.58c.013-.003.026-.008.038-.013l.033-.012.037-.018.032-.016.034-.02.032-.02.03-.022.031-.023.03-.028.026-.024a.778.778 0 00.044-.049l.008-.009.007-.01a.781.781 0 00.038-.052l.02-.032.019-.034.018-.037c.005-.01.01-.02.014-.032l.014-.038.012-.034.01-.037.008-.037.005-.038.005-.038.003-.045v-.031a.75.75 0 00-.004-.078l-1.744-16.596a.78.78 0 00-.858-.695l-24.458 2.57-.02.004a.719.719 0 00-.224.064.746.746 0 00-.245.178.955.955 0 00-.052.061l-.013.017-.006.01a.739.739 0 00-.025.038l-.016.028a.797.797 0 00-.02.038l-.015.032-.014.037c-.004.012-.009.023-.012.035-.004.012-.008.024-.01.037l-.01.036-.006.037-.006.039-.003.036a.945.945 0 00-.002.04l.001.035.002.044.001.013.504 4.788a.78.78 0 101.552-.163l-.322-3.064 7.546 5.386a.787.787 0 00-.094.097L18.9 27.45l-.536-5.103a.78.78 0 10-1.553.163l.1.949-4.012.422a.78.78 0 10.163 1.552l4.012-.421.47 4.475a.78.78 0 00.858.695l24.459-2.57a.788.788 0 00.076-.012l.034-.009.04-.01zm-1.205-2.538l-7.516-6.085-.014-.01 4.656-4.876a.78.78 0 10-1.129-1.078l-7.957 8.33-11.14-7.953 21.634-2.273 1.466 13.945zM26.2 20.917a.779.779 0 00.116-.197l3.174 2.266a.78.78 0 001.018-.096l2.696-2.822c.03.037.065.071.104.102l7.515 6.086-20.709 2.177 6.086-7.515z"/><path d="M7.634 21.905l13.113-1.379a.78.78 0 10-.163-1.552L7.47 20.352a.78.78 0 10.164 1.553zM8.543 17.483l5.888-.619a.78.78 0 10-.163-1.553l-5.889.62a.78.78 0 10.164 1.552zM13.935 27.087l-5.694.598a.78.78 0 10.163 1.553l5.694-.599a.78.78 0 10-.163-1.552z"/></g><defs><linearGradient id="paint0_linear" x1="-3.833" y1="62.533" x2="40.708" y2="43.044" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset=".653" stop-color="#FDAD1F"/><stop offset="1" stop-color="#FDC51D"/></linearGradient><clipPath id="clip0"><path fill="#fff" transform="rotate(-6 43.686 -45.544)" d="M0 0h36v36H0z"/></clipPath></defs></svg>
                                Modèles de lettre adapté pour chaque marque
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col-reverse flex-wrap justify-between items-center py-6 md:py-12">
                    <div class="w-full md:w-2/3 md:pr-24">
                        <div class="text-xl md:text-2xl text-gray-400 font-semibold">Un outil conçu pour vous</div>
                        <div class="text-3xl md:text-3xl font-semibold pb-3 md:pb-0">Simple & facile</div>
                        <div class="text-lg">Oubliez les formulaires compliqués et difficiles à trouver pour toutes vos résiliations</div>
                        <div class="flex pt-3 gap-6 md:gap-12 font-semibold">
                            <div class="w-1/2">
                                <svg width="45" height="42" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="14" cy="27.446" r="13" stroke="url(#paint3_linear)" stroke-width="2"/><g clip-path="url(#clip1)" fill="#FCFCFC"><path d="M43.012 27.027l.038-.014.033-.012.037-.018.032-.015.034-.02.032-.02.03-.023.031-.023.03-.028.026-.024a.743.743 0 00.044-.049l.008-.008.007-.01a.82.82 0 00.038-.053l.02-.032.019-.033.018-.038c.005-.01.01-.02.014-.032l.014-.038.012-.034.01-.037.008-.037.005-.038.005-.038.003-.044v-.032a.81.81 0 00-.003-.078L41.812 9.603a.78.78 0 00-.858-.695L16.496 11.48c-.007 0-.013.002-.02.003a.806.806 0 00-.29.099.76.76 0 00-.18.143l-.024.028c-.01.01-.018.022-.027.033l-.013.017-.006.01a.68.68 0 00-.025.038l-.016.029a.794.794 0 00-.02.037l-.015.032-.014.037c-.004.012-.009.023-.012.035-.004.012-.008.025-.01.037l-.01.037-.006.037c-.002.012-.005.025-.006.038l-.003.036a.942.942 0 00-.002.041l.001.034.002.044.001.013.504 4.788a.78.78 0 101.552-.163l-.322-3.064 7.546 5.386a.796.796 0 00-.094.097L18.9 26.896l-.536-5.103a.78.78 0 10-1.553.163l.1.95-4.012.42a.78.78 0 10.163 1.554l4.012-.422.47 4.476a.78.78 0 00.859.694l24.458-2.57a.788.788 0 00.076-.012c.012-.002.023-.006.034-.009l.04-.01zm-1.205-2.539l-7.516-6.085-.014-.01 4.656-4.876a.78.78 0 00-1.129-1.078l-7.957 8.33-11.14-7.952 21.634-2.274 1.466 13.945zM26.2 20.364a.777.777 0 00.116-.197l3.174 2.266a.78.78 0 001.018-.097l2.696-2.822c.03.037.065.071.104.103l7.515 6.085-20.709 2.177 6.086-7.515z"/><path d="M7.634 21.35l13.113-1.377a.78.78 0 10-.163-1.553L7.47 19.798a.78.78 0 10.164 1.553zM8.543 16.93l5.888-.62a.78.78 0 10-.163-1.552l-5.889.618a.78.78 0 10.164 1.553zM13.935 26.533l-5.694.598a.78.78 0 10.163 1.553l5.694-.598a.78.78 0 10-.163-1.553z"/></g><defs><linearGradient id="paint3_linear" x1="0" y1="13.446" x2="33.754" y2="28.787" gradientUnits="userSpaceOnUse"><stop stop-color="#2F64ED"/><stop offset="1" stop-color="#12E7F4"/></linearGradient><clipPath id="clip1"><path fill="#fff" transform="rotate(-6 38.402 -45.821)" d="M0 0h36v36H0z"/></clipPath></defs></svg>
                                Adresses de destinations remplies pour vous
                            </div>
                            <div class="w-1/2">
                                <svg width="45" height="42" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="14" cy="27.446" r="13" stroke="url(#paint3_linear)" stroke-width="2"/><g clip-path="url(#clip1)" fill="#FCFCFC"><path d="M43.012 27.027l.038-.014.033-.012.037-.018.032-.015.034-.02.032-.02.03-.023.031-.023.03-.028.026-.024a.743.743 0 00.044-.049l.008-.008.007-.01a.82.82 0 00.038-.053l.02-.032.019-.033.018-.038c.005-.01.01-.02.014-.032l.014-.038.012-.034.01-.037.008-.037.005-.038.005-.038.003-.044v-.032a.81.81 0 00-.003-.078L41.812 9.603a.78.78 0 00-.858-.695L16.496 11.48c-.007 0-.013.002-.02.003a.806.806 0 00-.29.099.76.76 0 00-.18.143l-.024.028c-.01.01-.018.022-.027.033l-.013.017-.006.01a.68.68 0 00-.025.038l-.016.029a.794.794 0 00-.02.037l-.015.032-.014.037c-.004.012-.009.023-.012.035-.004.012-.008.025-.01.037l-.01.037-.006.037c-.002.012-.005.025-.006.038l-.003.036a.942.942 0 00-.002.041l.001.034.002.044.001.013.504 4.788a.78.78 0 101.552-.163l-.322-3.064 7.546 5.386a.796.796 0 00-.094.097L18.9 26.896l-.536-5.103a.78.78 0 10-1.553.163l.1.95-4.012.42a.78.78 0 10.163 1.554l4.012-.422.47 4.476a.78.78 0 00.859.694l24.458-2.57a.788.788 0 00.076-.012c.012-.002.023-.006.034-.009l.04-.01zm-1.205-2.539l-7.516-6.085-.014-.01 4.656-4.876a.78.78 0 00-1.129-1.078l-7.957 8.33-11.14-7.952 21.634-2.274 1.466 13.945zM26.2 20.364a.777.777 0 00.116-.197l3.174 2.266a.78.78 0 001.018-.097l2.696-2.822c.03.037.065.071.104.103l7.515 6.085-20.709 2.177 6.086-7.515z"/><path d="M7.634 21.35l13.113-1.377a.78.78 0 10-.163-1.553L7.47 19.798a.78.78 0 10.164 1.553zM8.543 16.93l5.888-.62a.78.78 0 10-.163-1.552l-5.889.618a.78.78 0 10.164 1.553zM13.935 26.533l-5.694.598a.78.78 0 10.163 1.553l5.694-.598a.78.78 0 10-.163-1.553z"/></g><defs><linearGradient id="paint3_linear" x1="0" y1="13.446" x2="33.754" y2="28.787" gradientUnits="userSpaceOnUse"><stop stop-color="#2F64ED"/><stop offset="1" stop-color="#12E7F4"/></linearGradient><clipPath id="clip1"><path fill="#fff" transform="rotate(-6 38.402 -45.821)" d="M0 0h36v36H0z"/></clipPath></defs></svg>
                                Modèles de lettre adaptés pour chaque marque
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-1/3 pb-6 md:pb-0">
                        <img src="{{ asset('/images/arg-2.gif') }}" alt="Une moyenne de 45 min de gagnée">
                    </div>
                </div>
                <div class="flex flex-wrap justify-between items-center pt-12">
                    <div class="w-full md:w-1/3 pb-6 md:pb-0">
                        <img src="{{ asset('/images/arg-3.gif') }}" alt="Une moyenne de 45 min de gagnée">
                    </div>
                    <div class="w-full md:w-2/3 md:pl-24">
                        <div class="text-xl md:text-2xl text-gray-400 font-semibold">Ecolo & écono</div>
                        <div class="text-2xl md:text-3xl font-semibold pb-3 md:pb-0">Une résiliation, une planète</div>
                        <div class="text-lg">En plus de gagner du temps pour vous, vous faites un geste pour la planète en évitant un trajet</div>
                        <div class="flex pt-3 gap-6 md:gap-12 font-semibold">
                            <div class="fmd:w-1/2">
                                <svg width="45" height="42" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="14" cy="27.446" r="13" stroke="url(#paint9_linear)" stroke-width="2"/><g clip-path="url(#clip0)" fill="#FCFCFC"><path d="M43.012 27.027l.038-.014.033-.012.037-.018.032-.016.034-.02.032-.02c.01-.006.02-.014.03-.022l.031-.023.03-.028.026-.024a.743.743 0 00.044-.049l.008-.008.007-.01a.818.818 0 00.038-.053l.02-.032.019-.034.018-.037c.005-.01.01-.02.014-.032l.014-.038.012-.034.01-.038.008-.036.005-.038.005-.038.003-.045v-.031a.778.778 0 00-.003-.078L41.812 9.603a.78.78 0 00-.858-.695l-24.458 2.57-.02.004a.802.802 0 00-.29.099.728.728 0 00-.179.143.73.73 0 00-.052.061l-.013.017-.006.01-.025.038-.016.029a.749.749 0 00-.02.037l-.015.032-.014.037c-.004.012-.009.023-.012.035-.004.012-.008.024-.01.037l-.01.036-.006.038c-.002.012-.005.025-.006.038l-.003.036-.002.04.001.035.002.044.001.013.504 4.788a.78.78 0 101.552-.163l-.322-3.064 7.546 5.386a.788.788 0 00-.094.097l-6.086 7.515-.536-5.103a.78.78 0 10-1.553.163l.1.95-4.012.42a.78.78 0 10.163 1.554l4.012-.422.47 4.476a.78.78 0 00.859.694l24.458-2.57a.784.784 0 00.076-.012l.034-.009.04-.01zm-1.205-2.539l-7.516-6.085-.014-.01 4.656-4.876a.78.78 0 00-1.129-1.078l-7.957 8.33-11.14-7.952 21.634-2.274 1.466 13.945zM26.2 20.363a.777.777 0 00.116-.196l3.174 2.265a.78.78 0 001.018-.096l2.696-2.822c.031.037.065.071.104.102l7.515 6.086-20.709 2.177 6.086-7.516z"/><path d="M7.634 21.35l13.113-1.378a.78.78 0 10-.163-1.553L7.47 19.799a.78.78 0 10.164 1.553zM8.543 16.929l5.888-.619a.78.78 0 10-.163-1.553l-5.889.62a.78.78 0 10.164 1.552zM13.935 26.533l-5.694.598a.78.78 0 10.163 1.553l5.694-.599a.78.78 0 10-.163-1.552z"/></g><defs><linearGradient id="paint9_linear" x1="0" y1="13.446" x2="33.754" y2="28.787" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient><clipPath id="clip0"><path fill="#fff" transform="rotate(-6 38.4 -45.821)" d="M0 0h36v36H0z"/></clipPath></defs></svg>
                                2 min de formulaire au lieu d’1 heure
                            </div>
                            <div class="md:w-1/2">
                                <svg width="45" height="42" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="14" cy="27.446" r="13" stroke="url(#paint9_linear)" stroke-width="2"/><g clip-path="url(#clip0)" fill="#FCFCFC"><path d="M43.012 27.027l.038-.014.033-.012.037-.018.032-.016.034-.02.032-.02c.01-.006.02-.014.03-.022l.031-.023.03-.028.026-.024a.743.743 0 00.044-.049l.008-.008.007-.01a.818.818 0 00.038-.053l.02-.032.019-.034.018-.037c.005-.01.01-.02.014-.032l.014-.038.012-.034.01-.038.008-.036.005-.038.005-.038.003-.045v-.031a.778.778 0 00-.003-.078L41.812 9.603a.78.78 0 00-.858-.695l-24.458 2.57-.02.004a.802.802 0 00-.29.099.728.728 0 00-.179.143.73.73 0 00-.052.061l-.013.017-.006.01-.025.038-.016.029a.749.749 0 00-.02.037l-.015.032-.014.037c-.004.012-.009.023-.012.035-.004.012-.008.024-.01.037l-.01.036-.006.038c-.002.012-.005.025-.006.038l-.003.036-.002.04.001.035.002.044.001.013.504 4.788a.78.78 0 101.552-.163l-.322-3.064 7.546 5.386a.788.788 0 00-.094.097l-6.086 7.515-.536-5.103a.78.78 0 10-1.553.163l.1.95-4.012.42a.78.78 0 10.163 1.554l4.012-.422.47 4.476a.78.78 0 00.859.694l24.458-2.57a.784.784 0 00.076-.012l.034-.009.04-.01zm-1.205-2.539l-7.516-6.085-.014-.01 4.656-4.876a.78.78 0 00-1.129-1.078l-7.957 8.33-11.14-7.952 21.634-2.274 1.466 13.945zM26.2 20.363a.777.777 0 00.116-.196l3.174 2.265a.78.78 0 001.018-.096l2.696-2.822c.031.037.065.071.104.102l7.515 6.086-20.709 2.177 6.086-7.516z"/><path d="M7.634 21.35l13.113-1.378a.78.78 0 10-.163-1.553L7.47 19.799a.78.78 0 10.164 1.553zM8.543 16.929l5.888-.619a.78.78 0 10-.163-1.553l-5.889.62a.78.78 0 10.164 1.552zM13.935 26.533l-5.694.598a.78.78 0 10.163 1.553l5.694-.599a.78.78 0 10-.163-1.552z"/></g><defs><linearGradient id="paint9_linear" x1="0" y1="13.446" x2="33.754" y2="28.787" gradientUnits="userSpaceOnUse"><stop stop-color="#00DF96"/><stop offset="1" stop-color="#06F0FF"/></linearGradient><clipPath id="clip0"><path fill="#fff" transform="rotate(-6 38.4 -45.821)" d="M0 0h36v36H0z"/></clipPath></defs></svg>
                                Modèle de lettre adaptés pour chaque marque
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50">
            <div class="max-w-screen-lg mx-auto py-12 md:py-24 px-6 flex gap-6 md:gap-12 flex-col">
                <div class="block w-full text-4xl md:text-5xl font-semibold text-center">Comment résilier facilement ?</div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="flex justify-center items-center">
                        <svg width="111" height="148" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.47 130.2h1.776v15.264h9.408V147H6.47v-16.8zM25.206 134.28c1.648 0 2.912.416 3.792 1.248.88.816 1.32 2.032 1.32 3.648V147h-1.632v-1.968c-.384.656-.952 1.168-1.704 1.536-.736.368-1.616.552-2.64.552-1.408 0-2.528-.336-3.36-1.008-.832-.672-1.248-1.56-1.248-2.664 0-1.072.384-1.936 1.152-2.592.784-.656 2.024-.984 3.72-.984h4.008v-.768c0-1.088-.304-1.912-.912-2.472-.608-.576-1.496-.864-2.664-.864-.8 0-1.568.136-2.304.408a5.644 5.644 0 00-1.896 1.08l-.768-1.272c.64-.544 1.408-.96 2.304-1.248a8.754 8.754 0 012.832-.456zm-.6 11.496c.96 0 1.784-.216 2.472-.648.688-.448 1.2-1.088 1.536-1.92v-2.064h-3.96c-2.16 0-3.24.752-3.24 2.256 0 .736.28 1.32.84 1.752.56.416 1.344.624 2.352.624zM42.228 129.192h1.704V147h-1.704v-17.808zM60.165 141.216h-10.56c.096 1.312.6 2.376 1.512 3.192.912.8 2.064 1.2 3.456 1.2.784 0 1.504-.136 2.16-.408a4.847 4.847 0 001.704-1.248l.96 1.104a5.388 5.388 0 01-2.112 1.536c-.832.352-1.752.528-2.76.528-1.296 0-2.448-.272-3.456-.816A6.077 6.077 0 0148.74 144c-.56-.976-.84-2.08-.84-3.312 0-1.232.264-2.336.792-3.312a5.933 5.933 0 012.208-2.28c.944-.544 2-.816 3.168-.816 1.168 0 2.216.272 3.144.816a5.764 5.764 0 012.184 2.28c.528.96.792 2.064.792 3.312l-.024.528zm-6.096-5.472c-1.216 0-2.24.392-3.072 1.176-.816.768-1.28 1.776-1.392 3.024h8.952c-.112-1.248-.584-2.256-1.416-3.024-.816-.784-1.84-1.176-3.072-1.176zM70.805 146.232a3.35 3.35 0 01-1.2.672 4.911 4.911 0 01-1.464.216c-1.184 0-2.096-.32-2.736-.96-.64-.64-.96-1.544-.96-2.712v-7.632H62.19v-1.44h2.256v-2.76h1.704v2.76h3.84v1.44h-3.84v7.536c0 .752.184 1.328.552 1.728.384.384.928.576 1.632.576a3.03 3.03 0 001.008-.168c.336-.112.624-.272.864-.48l.6 1.224zM80.727 146.232a3.35 3.35 0 01-1.2.672 4.911 4.911 0 01-1.464.216c-1.184 0-2.096-.32-2.736-.96-.64-.64-.96-1.544-.96-2.712v-7.632h-2.256v-1.44h2.256v-2.76h1.704v2.76h3.84v1.44h-3.84v7.536c0 .752.184 1.328.552 1.728.384.384.928.576 1.632.576a3.03 3.03 0 001.008-.168c.336-.112.624-.272.864-.48l.6 1.224zM85.961 136.848c.4-.848.992-1.488 1.776-1.92.8-.432 1.784-.648 2.952-.648v1.656l-.408-.024c-1.328 0-2.368.408-3.12 1.224-.752.816-1.128 1.96-1.128 3.432V147H84.33v-12.624h1.632v2.472zM105.149 141.216h-10.56c.096 1.312.6 2.376 1.512 3.192.912.8 2.064 1.2 3.456 1.2.784 0 1.504-.136 2.16-.408a4.847 4.847 0 001.704-1.248l.96 1.104a5.388 5.388 0 01-2.112 1.536c-.832.352-1.752.528-2.76.528-1.296 0-2.448-.272-3.456-.816A6.077 6.077 0 0193.725 144c-.56-.976-.84-2.08-.84-3.312 0-1.232.264-2.336.792-3.312a5.933 5.933 0 012.208-2.28c.944-.544 2-.816 3.168-.816 1.168 0 2.216.272 3.144.816a5.764 5.764 0 012.184 2.28c.528.96.792 2.064.792 3.312l-.024.528zm-6.096-5.472c-1.216 0-2.24.392-3.072 1.176-.816.768-1.28 1.776-1.392 3.024h8.952c-.112-1.248-.584-2.256-1.416-3.024-.816-.784-1.84-1.176-3.072-1.176zM12.824 110l-3.645-5.265H5.156V110H.782V91.1h8.181c1.674 0 3.123.279 4.347.837 1.242.558 2.196 1.35 2.862 2.376.666 1.026 1 2.241 1 3.645s-.343 2.619-1.027 3.645c-.666 1.008-1.62 1.782-2.862 2.322l4.24 6.075h-4.699zm-.08-12.042c0-1.062-.343-1.872-1.027-2.43-.684-.576-1.683-.864-2.997-.864H5.156v6.588H8.72c1.314 0 2.313-.288 2.997-.864.684-.576 1.026-1.386 1.026-2.43zM34.769 102.791c0 .054-.027.432-.081 1.134h-10.99c.199.9.667 1.611 1.405 2.133s1.656.783 2.754.783c.756 0 1.422-.108 1.998-.324a5.017 5.017 0 001.647-1.08l2.24 2.43c-1.367 1.566-3.365 2.349-5.993 2.349-1.638 0-3.087-.315-4.347-.945-1.26-.648-2.232-1.539-2.916-2.673-.684-1.134-1.026-2.421-1.026-3.861 0-1.422.333-2.7.999-3.834a7.088 7.088 0 012.78-2.673c1.189-.648 2.512-.972 3.97-.972 1.422 0 2.709.306 3.86.918a6.59 6.59 0 012.7 2.646c.667 1.134 1 2.457 1 3.969zm-7.533-4.347c-.954 0-1.755.27-2.403.81-.648.54-1.044 1.278-1.188 2.214H30.8c-.144-.918-.54-1.647-1.188-2.187-.648-.558-1.44-.837-2.376-.837zM56.376 95.258c1.818 0 3.258.54 4.32 1.62 1.08 1.062 1.62 2.664 1.62 4.806V110h-4.212v-7.668c0-1.152-.243-2.007-.73-2.565-.467-.576-1.142-.864-2.024-.864-.99 0-1.773.324-2.35.972-.575.63-.863 1.575-.863 2.835V110h-4.212v-7.668c0-2.286-.918-3.429-2.754-3.429-.972 0-1.746.324-2.322.972-.576.63-.864 1.575-.864 2.835V110h-4.212V95.474h4.023v1.674a5.34 5.34 0 011.97-1.404 6.78 6.78 0 012.593-.486c1.026 0 1.953.207 2.78.621a4.829 4.829 0 011.999 1.755 5.983 5.983 0 012.24-1.755c.919-.414 1.918-.621 2.998-.621zM74.877 95.258c1.35 0 2.574.315 3.672.945a6.549 6.549 0 012.62 2.619c.63 1.116.944 2.421.944 3.915 0 1.494-.315 2.808-.945 3.942a6.782 6.782 0 01-2.619 2.619c-1.098.612-2.322.918-3.672.918-1.854 0-3.312-.585-4.374-1.755v6.777h-4.212V95.474h4.023v1.674c1.044-1.26 2.565-1.89 4.563-1.89zm-.729 11.502c1.08 0 1.962-.36 2.646-1.08.702-.738 1.053-1.719 1.053-2.943s-.35-2.196-1.053-2.916c-.684-.738-1.566-1.107-2.646-1.107-1.08 0-1.97.369-2.673 1.107-.684.72-1.026 1.692-1.026 2.916s.342 2.205 1.026 2.943c.702.72 1.593 1.08 2.673 1.08zM85.133 89.966h4.212V110h-4.212V89.966zM93.454 95.474h4.212V110h-4.212V95.474zm2.106-2.025c-.774 0-1.404-.225-1.89-.675a2.19 2.19 0 01-.73-1.674c0-.666.244-1.224.73-1.674.486-.45 1.116-.675 1.89-.675.774 0 1.404.216 1.89.648.486.432.729.972.729 1.62 0 .702-.243 1.287-.73 1.755-.485.45-1.115.675-1.89.675zM105.798 97.391c.504-.702 1.179-1.233 2.025-1.593.864-.36 1.854-.54 2.97-.54v3.888a15.201 15.201 0 00-.945-.054c-1.206 0-2.151.342-2.835 1.026-.684.666-1.026 1.674-1.026 3.024V110h-4.212V95.474h4.023v1.917z" fill="#333"/><circle cx="56" cy="38" r="36" stroke="url(#paint0_linear)" stroke-width="4"/><path d="M60.207 21.8V54h-7.452V27.78h-6.44V21.8h13.892z" fill="#333"/><defs><linearGradient id="paint0_linear" x1="7.595" y1="131.733" x2="128.494" y2="78.835" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset=".653" stop-color="#FDAD1F"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                    </div>
                    <div class="flex justify-center items-center">
                        <svg width="216" height="148" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="77" cy="38" r="36" stroke="url(#paint0_linear)" stroke-width="4"/><path d="M89.786 47.928V54H65.452v-4.83l12.42-11.73c1.318-1.257 2.208-2.33 2.668-3.22.46-.92.69-1.825.69-2.714 0-1.288-.445-2.27-1.334-2.944-.859-.705-2.132-1.058-3.818-1.058-1.411 0-2.684.276-3.818.828-1.135.521-2.086 1.319-2.852 2.392l-5.428-3.496c1.257-1.87 2.99-3.327 5.198-4.37 2.208-1.073 4.738-1.61 7.59-1.61 2.392 0 4.477.399 6.256 1.196 1.809.767 3.204 1.87 4.186 3.312 1.012 1.41 1.518 3.082 1.518 5.014 0 1.748-.368 3.389-1.104 4.922-.736 1.533-2.162 3.281-4.278 5.244l-7.406 6.992h13.846zM46.43 91.1c1.675 0 3.124.28 4.348.837 1.242.558 2.196 1.35 2.862 2.376.666 1.026.999 2.241.999 3.645 0 1.386-.333 2.601-1 3.645-.665 1.026-1.62 1.818-2.861 2.376-1.224.54-2.673.81-4.347.81h-3.807V110H38.25V91.1h8.18zm-.242 10.125c1.314 0 2.313-.279 2.997-.837.684-.576 1.026-1.386 1.026-2.43 0-1.062-.342-1.872-1.026-2.43-.684-.576-1.683-.864-2.997-.864h-3.564v6.561h3.564zM63.392 95.258c2.25 0 3.978.54 5.184 1.62 1.206 1.062 1.809 2.673 1.809 4.833V110h-3.942v-1.809c-.792 1.35-2.268 2.025-4.428 2.025-1.116 0-2.088-.189-2.916-.567-.81-.378-1.431-.9-1.863-1.566-.432-.666-.648-1.422-.648-2.268 0-1.35.504-2.412 1.512-3.186 1.026-.774 2.6-1.161 4.725-1.161h3.348c0-.918-.28-1.62-.837-2.106-.558-.504-1.395-.756-2.511-.756-.774 0-1.54.126-2.295.378-.738.234-1.368.558-1.89.972l-1.512-2.943c.792-.558 1.737-.99 2.835-1.296a12.909 12.909 0 013.429-.459zm-.324 12.123c.72 0 1.359-.162 1.917-.486.558-.342.954-.837 1.188-1.485v-1.485h-2.89c-1.727 0-2.591.567-2.591 1.701 0 .54.207.972.62 1.296.433.306 1.018.459 1.756.459zM88.551 95.474l-6.561 15.417c-.666 1.674-1.494 2.853-2.484 3.537-.972.684-2.151 1.026-3.537 1.026a7.374 7.374 0 01-2.241-.351c-.738-.234-1.341-.558-1.809-.972l1.539-2.997c.324.288.693.513 1.107.675a3.6 3.6 0 001.269.243c.576 0 1.044-.144 1.404-.432.36-.27.684-.729.972-1.377l.054-.135-6.291-14.634h4.347l4.077 9.855 4.104-9.855h4.05zM104.303 102.791c0 .054-.027.432-.081 1.134H93.233c.198.9.666 1.611 1.404 2.133.738.522 1.656.783 2.754.783.756 0 1.422-.108 1.998-.324a5.017 5.017 0 001.647-1.08l2.241 2.43c-1.368 1.566-3.366 2.349-5.994 2.349-1.638 0-3.087-.315-4.347-.945-1.26-.648-2.232-1.539-2.916-2.673-.684-1.134-1.026-2.421-1.026-3.861 0-1.422.333-2.7 1-3.834a7.088 7.088 0 012.78-2.673c1.188-.648 2.511-.972 3.97-.972 1.421 0 2.708.306 3.86.918a6.59 6.59 0 012.7 2.646c.666 1.134.999 2.457.999 3.969zm-7.533-4.347c-.954 0-1.755.27-2.403.81-.648.54-1.044 1.278-1.188 2.214h7.155c-.144-.918-.54-1.647-1.188-2.187-.648-.558-1.44-.837-2.376-.837zM111.33 97.391c.504-.702 1.179-1.233 2.025-1.593.864-.36 1.854-.54 2.97-.54v3.888a15.201 15.201 0 00-.945-.054c-1.206 0-2.151.342-2.835 1.026-.684.666-1.026 1.674-1.026 3.024V110h-4.212V95.474h4.023v1.917zM.412 130.2h1.776v15.264h9.408V147H.412v-16.8zM19.148 134.28c1.648 0 2.912.416 3.791 1.248.88.816 1.32 2.032 1.32 3.648V147h-1.631v-1.968c-.384.656-.953 1.168-1.704 1.536-.736.368-1.616.552-2.64.552-1.409 0-2.528-.336-3.36-1.008-.832-.672-1.248-1.56-1.248-2.664 0-1.072.383-1.936 1.152-2.592.784-.656 2.024-.984 3.72-.984h4.008v-.768c0-1.088-.305-1.912-.913-2.472-.608-.576-1.495-.864-2.663-.864-.8 0-1.569.136-2.305.408a5.644 5.644 0 00-1.896 1.08l-.768-1.272c.64-.544 1.409-.96 2.305-1.248a8.754 8.754 0 012.832-.456zm-.6 11.496c.96 0 1.784-.216 2.472-.648.688-.448 1.2-1.088 1.535-1.92v-2.064h-3.96c-2.16 0-3.24.752-3.24 2.256 0 .736.28 1.32.84 1.752.56.416 1.345.624 2.352.624zM37.801 136.848c.4-.848.992-1.488 1.776-1.92.8-.432 1.784-.648 2.952-.648v1.656l-.408-.024c-1.328 0-2.368.408-3.12 1.224-.752.816-1.128 1.96-1.128 3.432V147h-1.704v-12.624h1.632v2.472zM56.99 141.216H46.43c.095 1.312.6 2.376 1.511 3.192.912.8 2.064 1.2 3.456 1.2.784 0 1.504-.136 2.16-.408a4.847 4.847 0 001.704-1.248l.96 1.104a5.388 5.388 0 01-2.112 1.536c-.832.352-1.752.528-2.76.528-1.296 0-2.448-.272-3.456-.816A6.077 6.077 0 0145.565 144c-.56-.976-.84-2.08-.84-3.312 0-1.232.264-2.336.792-3.312a5.933 5.933 0 012.208-2.28c.944-.544 2-.816 3.168-.816 1.168 0 2.216.272 3.144.816a5.764 5.764 0 012.184 2.28c.528.96.792 2.064.792 3.312l-.024.528zm-6.097-5.472c-1.216 0-2.24.392-3.072 1.176-.816.768-1.28 1.776-1.392 3.024h8.952c-.112-1.248-.584-2.256-1.416-3.024-.816-.784-1.84-1.176-3.072-1.176zm1.728-6.312h2.28l-3.888 2.976h-1.68l3.288-2.976zM64.365 147.12a10.06 10.06 0 01-2.951-.432c-.928-.304-1.657-.68-2.184-1.128l.768-1.344c.528.416 1.192.76 1.992 1.032a8.14 8.14 0 002.495.384c1.153 0 2-.176 2.544-.528.56-.368.84-.88.84-1.536 0-.464-.151-.824-.456-1.08-.303-.272-.688-.472-1.151-.6-.465-.144-1.08-.28-1.849-.408-1.023-.192-1.847-.384-2.471-.576a3.817 3.817 0 01-1.608-1.032c-.433-.48-.649-1.144-.649-1.992 0-1.056.44-1.92 1.32-2.592.88-.672 2.104-1.008 3.672-1.008.816 0 1.632.112 2.448.336.817.208 1.489.488 2.016.84l-.744 1.368c-1.04-.72-2.28-1.08-3.72-1.08-1.088 0-1.912.192-2.472.576-.544.384-.816.888-.816 1.512 0 .48.152.864.456 1.152.32.288.712.504 1.177.648.464.128 1.104.264 1.92.408 1.008.192 1.816.384 2.424.576.608.192 1.127.52 1.56.984.431.464.647 1.104.647 1.92 0 1.104-.463 1.984-1.391 2.64-.913.64-2.184.96-3.817.96zM73.224 134.376h1.704V147h-1.704v-12.624zm.864-2.76c-.352 0-.648-.12-.888-.36s-.36-.528-.36-.864c0-.32.12-.6.36-.84s.536-.36.888-.36.648.12.888.36c.24.224.36.496.36.816 0 .352-.12.648-.36.888s-.536.36-.888.36zM80.169 129.192h1.704V147h-1.704v-17.808zM87.114 134.376h1.704V147h-1.704v-12.624zm.864-2.76c-.352 0-.648-.12-.888-.36s-.36-.528-.36-.864c0-.32.12-.6.36-.84s.536-.36.888-.36.648.12.888.36c.24.224.36.496.36.816 0 .352-.12.648-.36.888s-.536.36-.888.36zM98.476 134.28c1.648 0 2.912.416 3.792 1.248.88.816 1.32 2.032 1.32 3.648V147h-1.632v-1.968c-.384.656-.952 1.168-1.704 1.536-.736.368-1.616.552-2.64.552-1.408 0-2.528-.336-3.36-1.008-.832-.672-1.248-1.56-1.248-2.664 0-1.072.384-1.936 1.152-2.592.784-.656 2.024-.984 3.72-.984h4.008v-.768c0-1.088-.304-1.912-.912-2.472-.608-.576-1.496-.864-2.664-.864-.8 0-1.568.136-2.304.408a5.644 5.644 0 00-1.896 1.08l-.768-1.272c.64-.544 1.408-.96 2.304-1.248a8.754 8.754 0 012.832-.456zm-.6 11.496c.96 0 1.784-.216 2.472-.648.688-.448 1.2-1.088 1.536-1.92v-2.064h-3.96c-2.16 0-3.24.752-3.24 2.256 0 .736.28 1.32.84 1.752.56.416 1.344.624 2.352.624zM115.364 146.232a3.35 3.35 0 01-1.2.672 4.911 4.911 0 01-1.464.216c-1.184 0-2.096-.32-2.736-.96-.64-.64-.96-1.544-.96-2.712v-7.632h-2.256v-1.44h2.256v-2.76h1.704v2.76h3.84v1.44h-3.84v7.536c0 .752.184 1.328.552 1.728.384.384.928.576 1.632.576a3.03 3.03 0 001.008-.168c.336-.112.624-.272.864-.48l.6 1.224zM118.966 134.376h1.704V147h-1.704v-12.624zm.864-2.76c-.352 0-.648-.12-.888-.36s-.36-.528-.36-.864c0-.32.12-.6.36-.84s.536-.36.888-.36.648.12.888.36c.24.224.36.496.36.816 0 .352-.12.648-.36.888s-.536.36-.888.36zM131.071 147.12c-1.216 0-2.312-.272-3.288-.816a6.126 6.126 0 01-2.304-2.304c-.56-.976-.84-2.08-.84-3.312 0-1.232.28-2.336.84-3.312a5.953 5.953 0 012.304-2.28c.976-.544 2.072-.816 3.288-.816 1.216 0 2.312.272 3.288.816a5.787 5.787 0 012.28 2.28c.56.976.84 2.08.84 3.312 0 1.232-.28 2.336-.84 3.312a5.953 5.953 0 01-2.28 2.304c-.976.544-2.072.816-3.288.816zm0-1.512c.896 0 1.696-.2 2.4-.6.72-.416 1.28-1 1.68-1.752.4-.752.6-1.608.6-2.568s-.2-1.816-.6-2.568a4.166 4.166 0 00-1.68-1.728c-.704-.416-1.504-.624-2.4-.624-.896 0-1.704.208-2.424.624-.704.4-1.264.976-1.68 1.728-.4.752-.6 1.608-.6 2.568s.2 1.816.6 2.568a4.541 4.541 0 001.68 1.752c.72.4 1.528.6 2.424.6zM147.89 134.28c1.584 0 2.84.464 3.768 1.392.944.912 1.416 2.248 1.416 4.008V147h-1.704v-7.152c0-1.312-.328-2.312-.984-3-.656-.688-1.592-1.032-2.808-1.032-1.36 0-2.44.408-3.24 1.224-.784.8-1.176 1.912-1.176 3.336V147h-1.704v-12.624h1.632v2.328a4.677 4.677 0 011.92-1.776c.832-.432 1.792-.648 2.88-.648z" fill="#333"/><defs><linearGradient id="paint0_linear" x1="28.595" y1="131.733" x2="149.494" y2="78.835" gradientUnits="userSpaceOnUse"><stop stop-color="#FB29CD"/><stop offset=".653" stop-color="#FDAD1F"/><stop offset="1" stop-color="#FDC51D"/></linearGradient></defs></svg>
                    </div>
                    <div class="col-span-full md:col-span-1 flex justify-center items-center">
                        <svg
                        width="236"
                        height="186"
                        fill="none"
                        version="1.1"
                        id="svg144"
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:svg="http://www.w3.org/2000/svg">
                        <circle
                            cx="57"
                            cy="40"
                            r="36"
                            stroke="url(#paint0_linear)"
                            stroke-width="4"
                            id="circle113"
                            style="stroke:url(#paint0_linear)" />
                        <circle
                            opacity="0.9"
                            cx="118.5"
                            cy="87.5"
                            r="87.5"
                            fill="url(#paint1_linear)"
                            id="circle115"
                            style="fill:url(#paint1_linear)" />
                        <rect
                            y="27"
                            width="236"
                            height="118"
                            rx="30"
                            fill="#ffffff"
                            id="rect117"
                            x="0" />
                        <path
                            d="m 40.613,68.276 -1.932,-1.794 c -1.534,1.196 -3.328,1.794 -5.382,1.794 -1.166,0 -2.216,-0.184 -3.151,-0.552 -0.936,-0.383 -1.672,-0.912 -2.208,-1.587 A 3.63,3.63 0 0 1 27.158,63.86 c 0,-0.997 0.276,-1.87 0.828,-2.622 0.567,-0.767 1.472,-1.495 2.714,-2.185 -0.583,-0.598 -1.005,-1.165 -1.265,-1.702 a 3.944,3.944 0 0 1 -0.391,-1.702 c 0,-0.782 0.207,-1.472 0.62,-2.07 0.415,-0.598 0.997,-1.058 1.749,-1.38 0.766,-0.337 1.648,-0.506 2.645,-0.506 1.395,0 2.514,0.337 3.358,1.012 0.843,0.66 1.265,1.556 1.265,2.691 0,0.828 -0.253,1.564 -0.76,2.208 -0.49,0.644 -1.287,1.265 -2.391,1.863 l 2.944,2.737 c 0.352,-0.736 0.62,-1.572 0.805,-2.507 l 2.875,0.897 c -0.307,1.426 -0.798,2.645 -1.472,3.657 l 1.886,1.748 z M 34.058,54.131 c -0.537,0 -0.966,0.138 -1.288,0.414 a 1.36,1.36 0 0 0 -0.46,1.058 c 0,0.322 0.084,0.629 0.253,0.92 0.168,0.276 0.498,0.652 0.989,1.127 0.782,-0.414 1.326,-0.782 1.633,-1.104 0.306,-0.337 0.46,-0.698 0.46,-1.081 0,-0.399 -0.138,-0.72 -0.414,-0.966 -0.276,-0.245 -0.667,-0.368 -1.173,-0.368 z M 33.552,65.47 c 1.119,0 2.123,-0.314 3.013,-0.943 l -3.887,-3.634 c -0.721,0.414 -1.235,0.82 -1.541,1.219 -0.307,0.399 -0.46,0.851 -0.46,1.357 0,0.598 0.26,1.081 0.782,1.449 0.52,0.368 1.219,0.552 2.093,0.552 z m 24.224,2.806 c -1.273,0 -2.507,-0.169 -3.703,-0.506 -1.181,-0.353 -2.132,-0.805 -2.852,-1.357 l 1.265,-2.806 c 0.69,0.506 1.51,0.912 2.46,1.219 0.951,0.307 1.902,0.46 2.853,0.46 1.058,0 1.84,-0.153 2.346,-0.46 0.506,-0.322 0.759,-0.744 0.759,-1.265 0,-0.383 -0.154,-0.698 -0.46,-0.943 -0.292,-0.26 -0.675,-0.468 -1.15,-0.621 A 21.657,21.657 0 0 0 57.408,61.491 C 56.181,61.2 55.177,60.908 54.395,60.617 a 4.982,4.982 0 0 1 -2.024,-1.403 c -0.552,-0.644 -0.828,-1.503 -0.828,-2.576 0,-0.935 0.253,-1.779 0.759,-2.53 0.506,-0.767 1.265,-1.372 2.277,-1.817 1.027,-0.445 2.277,-0.667 3.749,-0.667 1.027,0 2.031,0.123 3.013,0.368 0.981,0.245 1.84,0.598 2.576,1.058 l -1.15,2.829 c -1.488,-0.843 -2.975,-1.265 -4.462,-1.265 -1.043,0 -1.817,0.169 -2.323,0.506 -0.491,0.337 -0.736,0.782 -0.736,1.334 0,0.552 0.283,0.966 0.85,1.242 0.583,0.26 1.465,0.521 2.646,0.782 1.226,0.291 2.23,0.583 3.013,0.874 a 4.902,4.902 0 0 1 2,1.38 c 0.568,0.629 0.852,1.48 0.852,2.553 0,0.92 -0.261,1.763 -0.782,2.53 -0.506,0.751 -1.273,1.35 -2.3,1.794 -1.028,0.445 -2.277,0.667 -3.75,0.667 z M 75.38,67.402 c -0.352,0.26 -0.789,0.46 -1.31,0.598 a 6.813,6.813 0 0 1 -1.61,0.184 c -1.472,0 -2.615,-0.376 -3.427,-1.127 -0.798,-0.751 -1.196,-1.855 -1.196,-3.312 v -5.083 h -1.91 v -2.76 h 1.91 v -3.013 h 3.588 v 3.013 h 3.082 v 2.76 h -3.082 v 5.037 c 0,0.521 0.13,0.928 0.39,1.219 0.277,0.276 0.66,0.414 1.15,0.414 0.568,0 1.051,-0.153 1.45,-0.46 l 0.966,2.53 z m 8.02,0.782 c -1.302,0 -2.475,-0.268 -3.518,-0.805 a 6.17,6.17 0 0 1 -2.415,-2.277 c -0.583,-0.966 -0.874,-2.062 -0.874,-3.289 0,-1.227 0.291,-2.323 0.874,-3.289 a 6.01,6.01 0 0 1 2.415,-2.254 c 1.043,-0.552 2.216,-0.828 3.519,-0.828 1.303,0 2.469,0.276 3.496,0.828 a 6.008,6.008 0 0 1 2.415,2.254 c 0.583,0.966 0.874,2.062 0.874,3.289 0,1.227 -0.291,2.323 -0.874,3.289 a 6.17,6.17 0 0 1 -2.415,2.277 c -1.027,0.537 -2.193,0.805 -3.496,0.805 z m 0,-2.944 c 0.92,0 1.672,-0.307 2.255,-0.92 0.598,-0.629 0.897,-1.464 0.897,-2.507 0,-1.043 -0.3,-1.87 -0.897,-2.484 -0.583,-0.629 -1.334,-0.943 -2.254,-0.943 -0.92,0 -1.68,0.314 -2.277,0.943 -0.598,0.613 -0.897,1.441 -0.897,2.484 0,1.043 0.299,1.878 0.897,2.507 0.598,0.613 1.357,0.92 2.277,0.92 z m 16.998,-9.798 c 1.15,0 2.193,0.268 3.128,0.805 a 5.573,5.573 0 0 1 2.231,2.231 c 0.537,0.95 0.805,2.062 0.805,3.335 0,1.273 -0.268,2.392 -0.805,3.358 a 5.77,5.77 0 0 1 -2.231,2.231 c -0.935,0.521 -1.978,0.782 -3.128,0.782 -1.58,0 -2.821,-0.498 -3.726,-1.495 v 5.773 H 93.084 V 55.626 h 3.427 v 1.426 c 0.89,-1.073 2.185,-1.61 3.887,-1.61 z m -0.62,9.798 c 0.919,0 1.671,-0.307 2.253,-0.92 0.598,-0.629 0.897,-1.464 0.897,-2.507 0,-1.043 -0.299,-1.87 -0.897,-2.484 -0.582,-0.629 -1.334,-0.943 -2.254,-0.943 -0.92,0 -1.679,0.314 -2.277,0.943 -0.582,0.613 -0.874,1.441 -0.874,2.484 0,1.043 0.292,1.878 0.874,2.507 0.598,0.613 1.357,0.92 2.277,0.92 z m 24.658,3.036 c -1.641,0 -3.128,-0.353 -4.462,-1.058 a 8.19,8.19 0 0 1 -3.128,-2.967 c -0.752,-1.273 -1.127,-2.706 -1.127,-4.301 0,-1.595 0.375,-3.02 1.127,-4.278 a 7.97,7.97 0 0 1 3.128,-2.967 c 1.334,-0.72 2.829,-1.081 4.485,-1.081 1.395,0 2.652,0.245 3.772,0.736 a 7.566,7.566 0 0 1 2.852,2.116 l -2.392,2.208 c -1.089,-1.257 -2.438,-1.886 -4.048,-1.886 -0.997,0 -1.886,0.222 -2.668,0.667 a 4.656,4.656 0 0 0 -1.84,1.817 c -0.43,0.782 -0.644,1.671 -0.644,2.668 0,0.997 0.214,1.886 0.644,2.668 a 4.816,4.816 0 0 0 1.84,1.84 c 0.782,0.43 1.671,0.644 2.668,0.644 1.61,0 2.959,-0.636 4.048,-1.909 l 2.392,2.208 a 7.462,7.462 0 0 1 -2.852,2.139 c -1.135,0.49 -2.4,0.736 -3.795,0.736 z m 14.871,-0.092 c -1.303,0 -2.476,-0.268 -3.519,-0.805 a 6.175,6.175 0 0 1 -2.415,-2.277 c -0.582,-0.966 -0.874,-2.062 -0.874,-3.289 0,-1.227 0.292,-2.323 0.874,-3.289 a 6.013,6.013 0 0 1 2.415,-2.254 c 1.043,-0.552 2.216,-0.828 3.519,-0.828 1.304,0 2.469,0.276 3.496,0.828 a 6,6 0 0 1 2.415,2.254 c 0.583,0.966 0.874,2.062 0.874,3.289 0,1.227 -0.291,2.323 -0.874,3.289 a 6.161,6.161 0 0 1 -2.415,2.277 c -1.027,0.537 -2.192,0.805 -3.496,0.805 z m 0,-2.944 c 0.92,0 1.672,-0.307 2.254,-0.92 0.598,-0.629 0.897,-1.464 0.897,-2.507 0,-1.043 -0.299,-1.87 -0.897,-2.484 -0.582,-0.629 -1.334,-0.943 -2.254,-0.943 -0.92,0 -1.679,0.314 -2.277,0.943 -0.598,0.613 -0.897,1.441 -0.897,2.484 0,1.043 0.299,1.878 0.897,2.507 0.598,0.613 1.357,0.92 2.277,0.92 z m 17.204,-9.798 c 1.534,0 2.768,0.46 3.703,1.38 0.951,0.92 1.426,2.285 1.426,4.094 V 68 h -3.588 v -6.532 c 0,-0.981 -0.214,-1.71 -0.644,-2.185 -0.429,-0.49 -1.05,-0.736 -1.863,-0.736 -0.904,0 -1.625,0.284 -2.162,0.851 -0.536,0.552 -0.805,1.38 -0.805,2.484 V 68 H 148.99 V 55.626 h 3.427 v 1.449 a 4.653,4.653 0 0 1 1.771,-1.196 6.031,6.031 0 0 1 2.323,-0.437 z m 16.863,11.96 c -0.353,0.26 -0.79,0.46 -1.311,0.598 a 6.815,6.815 0 0 1 -1.61,0.184 c -1.472,0 -2.615,-0.376 -3.427,-1.127 -0.798,-0.751 -1.196,-1.855 -1.196,-3.312 v -5.083 h -1.909 v -2.76 h 1.909 v -3.013 h 3.588 v 3.013 h 3.082 v 2.76 h -3.082 v 5.037 c 0,0.521 0.13,0.928 0.391,1.219 0.276,0.276 0.659,0.414 1.15,0.414 0.567,0 1.05,-0.153 1.449,-0.46 z m 5.941,-10.143 a 3.997,3.997 0 0 1 1.725,-1.357 c 0.736,-0.307 1.579,-0.46 2.53,-0.46 v 3.312 c -0.399,-0.03 -0.667,-0.046 -0.805,-0.046 -1.027,0 -1.832,0.291 -2.415,0.874 -0.583,0.567 -0.874,1.426 -0.874,2.576 V 68 h -3.588 V 55.626 h 3.427 z m 11.812,-1.817 c 1.917,0 3.389,0.46 4.416,1.38 1.027,0.905 1.541,2.277 1.541,4.117 V 68 h -3.358 v -1.541 c -0.675,1.15 -1.932,1.725 -3.772,1.725 -0.951,0 -1.779,-0.161 -2.484,-0.483 -0.69,-0.322 -1.219,-0.767 -1.587,-1.334 a 3.472,3.472 0 0 1 -0.552,-1.932 c 0,-1.15 0.429,-2.055 1.288,-2.714 0.874,-0.66 2.216,-0.989 4.025,-0.989 h 2.852 c 0,-0.782 -0.238,-1.38 -0.713,-1.794 -0.475,-0.43 -1.188,-0.644 -2.139,-0.644 a 6.15,6.15 0 0 0 -1.955,0.322 c -0.629,0.2 -1.165,0.475 -1.61,0.828 l -1.288,-2.507 c 0.675,-0.475 1.48,-0.843 2.415,-1.104 0.951,-0.26 1.924,-0.391 2.921,-0.391 z m -0.276,10.327 c 0.613,0 1.158,-0.138 1.633,-0.414 a 2.382,2.382 0 0 0 1.012,-1.265 v -1.265 h -2.461 c -1.472,0 -2.208,0.483 -2.208,1.449 0,0.46 0.176,0.828 0.529,1.104 0.368,0.26 0.866,0.391 1.495,0.391 z m 17.961,1.633 c -0.352,0.26 -0.789,0.46 -1.311,0.598 a 6.81,6.81 0 0 1 -1.61,0.184 c -1.472,0 -2.614,-0.376 -3.427,-1.127 -0.797,-0.751 -1.196,-1.855 -1.196,-3.312 v -5.083 h -1.909 v -2.76 h 1.909 v -3.013 h 3.588 v 3.013 h 3.082 v 2.76 h -3.082 v 5.037 c 0,0.521 0.131,0.928 0.391,1.219 0.276,0.276 0.66,0.414 1.15,0.414 0.568,0 1.051,-0.153 1.449,-0.46 z M 48.98,95.184 c -1.027,0 -2.031,-0.123 -3.012,-0.368 -0.982,-0.26 -1.764,-0.583 -2.346,-0.966 l 1.196,-2.576 c 0.552,0.353 1.219,0.644 2,0.874 a 8.664,8.664 0 0 0 2.3,0.322 c 1.519,0 2.278,-0.376 2.278,-1.127 0,-0.353 -0.207,-0.606 -0.621,-0.759 -0.414,-0.153 -1.05,-0.284 -1.91,-0.391 -1.011,-0.153 -1.847,-0.33 -2.506,-0.529 a 3.97,3.97 0 0 1 -1.725,-1.058 c -0.476,-0.506 -0.713,-1.227 -0.713,-2.162 0,-0.782 0.222,-1.472 0.667,-2.07 0.46,-0.613 1.119,-1.089 1.978,-1.426 0.874,-0.337 1.901,-0.506 3.082,-0.506 0.874,0 1.74,0.1 2.599,0.299 0.874,0.184 1.594,0.445 2.162,0.782 l -1.196,2.553 a 7.14,7.14 0 0 0 -3.565,-0.92 c -0.767,0 -1.342,0.107 -1.725,0.322 -0.384,0.215 -0.575,0.49 -0.575,0.828 0,0.383 0.207,0.652 0.62,0.805 0.415,0.153 1.074,0.299 1.979,0.437 1.012,0.169 1.84,0.353 2.484,0.552 a 3.644,3.644 0 0 1 1.679,1.035 c 0.475,0.506 0.713,1.211 0.713,2.116 0,0.767 -0.23,1.449 -0.69,2.047 -0.46,0.598 -1.135,1.066 -2.024,1.403 -0.874,0.322 -1.917,0.483 -3.128,0.483 z M 58.916,77.75 c 0.644,0 1.173,0.207 1.587,0.621 0.414,0.399 0.621,0.928 0.621,1.587 0,0.307 -0.038,0.613 -0.115,0.92 -0.076,0.307 -0.237,0.767 -0.483,1.38 l -1.334,3.312 h -2.277 l 0.99,-3.634 a 1.979,1.979 0 0 1 -0.898,-0.759 c -0.214,-0.337 -0.322,-0.744 -0.322,-1.219 0,-0.66 0.207,-1.188 0.621,-1.587 0.43,-0.414 0.966,-0.621 1.61,-0.621 z m 10.466,17.434 c -1.303,0 -2.476,-0.268 -3.519,-0.805 a 6.17,6.17 0 0 1 -2.415,-2.277 c -0.582,-0.966 -0.874,-2.062 -0.874,-3.289 0,-1.227 0.292,-2.323 0.874,-3.289 a 6.01,6.01 0 0 1 2.415,-2.254 c 1.043,-0.552 2.216,-0.828 3.52,-0.828 1.303,0 2.468,0.276 3.495,0.828 a 6.01,6.01 0 0 1 2.415,2.254 c 0.583,0.966 0.874,2.062 0.874,3.289 0,1.227 -0.29,2.323 -0.874,3.289 a 6.17,6.17 0 0 1 -2.415,2.277 c -1.027,0.537 -2.192,0.805 -3.496,0.805 z m 0,-2.944 c 0.92,0 1.672,-0.307 2.254,-0.92 0.598,-0.629 0.897,-1.464 0.897,-2.507 0,-1.043 -0.299,-1.87 -0.897,-2.484 -0.582,-0.629 -1.334,-0.943 -2.254,-0.943 -0.92,0 -1.679,0.314 -2.277,0.943 -0.598,0.613 -0.897,1.441 -0.897,2.484 0,1.043 0.3,1.878 0.897,2.507 0.598,0.613 1.357,0.92 2.277,0.92 z m 15.664,2.944 c -1.319,0 -2.507,-0.268 -3.565,-0.805 A 6.297,6.297 0 0 1 79.02,92.102 c -0.583,-0.966 -0.874,-2.062 -0.874,-3.289 0,-1.227 0.291,-2.323 0.874,-3.289 a 6.133,6.133 0 0 1 2.46,-2.254 c 1.059,-0.552 2.247,-0.828 3.566,-0.828 1.303,0 2.438,0.276 3.404,0.828 a 4.772,4.772 0 0 1 2.139,2.323 l -2.
                        783,1.495 c -0.644,-1.135 -1.572,-1.702 -2.783,-1.702 -0.936,0 -1.71,0.307 -2.323,0.92 -0.614,0.613 -0.92,1.449 -0.92,2.507 0,1.058 0.306,1.894 0.92,2.507 0.613,0.613 1.387,0.92 2.323,0.92 1.226,0 2.154,-0.567 2.783,-1.702 l 2.783,1.518 c -0.445,0.981 -1.158,1.748 -2.14,2.3 -0.965,0.552 -2.1,0.828 -3.403,0.828 z m 13.931,0 c -1.318,0 -2.507,-0.268 -3.565,-0.805 a 6.297,6.297 0 0 1 -2.46,-2.277 c -0.583,-0.966 -0.875,-2.062 -0.875,-3.289 0,-1.227 0.292,-2.323 0.874,-3.289 a 6.133,6.133 0 0 1 2.461,-2.254 c 1.058,-0.552 2.247,-0.828 3.565,-0.828 1.304,0 2.438,0.276 3.404,0.828 a 4.766,4.766 0 0 1 2.139,2.323 l -2.783,1.495 c -0.644,-1.135 -1.571,-1.702 -2.783,-1.702 -0.935,0 -1.71,0.307 -2.323,0.92 -0.613,0.613 -0.92,1.449 -0.92,2.507 0,1.058 0.307,1.894 0.92,2.507 0.614,0.613 1.388,0.92 2.323,0.92 1.227,0 2.155,-0.567 2.783,-1.702 l 2.783,1.518 c -0.444,0.981 -1.157,1.748 -2.139,2.3 -0.966,0.552 -2.1,0.828 -3.404,0.828 z M 119.552,82.626 V 95 h -3.404 v -1.472 a 4.873,4.873 0 0 1 -1.702,1.242 5.48,5.48 0 0 1 -2.139,0.414 c -1.625,0 -2.913,-0.468 -3.864,-1.403 -0.95,-0.935 -1.426,-2.323 -1.426,-4.163 v -6.992 h 3.588 v 6.463 c 0,1.993 0.836,2.99 2.507,2.99 0.859,0 1.549,-0.276 2.07,-0.828 0.522,-0.567 0.782,-1.403 0.782,-2.507 v -6.118 z m 11.138,-0.184 c 1.15,0 2.193,0.268 3.128,0.805 a 5.573,5.573 0 0 1 2.231,2.231 c 0.537,0.95 0.805,2.062 0.805,3.335 0,1.273 -0.268,2.392 -0.805,3.358 a 5.77,5.77 0 0 1 -2.231,2.231 c -0.935,0.521 -1.978,0.782 -3.128,0.782 -1.579,0 -2.821,-0.498 -3.726,-1.495 v 5.773 h -3.588 V 82.626 h 3.427 v 1.426 c 0.89,-1.073 2.185,-1.61 3.887,-1.61 z m -0.621,9.798 c 0.92,0 1.672,-0.307 2.254,-0.92 0.598,-0.629 0.897,-1.464 0.897,-2.507 0,-1.043 -0.299,-1.87 -0.897,-2.484 -0.582,-0.629 -1.334,-0.943 -2.254,-0.943 -0.92,0 -1.679,0.314 -2.277,0.943 -0.582,0.613 -0.874,1.441 -0.874,2.484 0,1.043 0.292,1.878 0.874,2.507 0.598,0.613 1.357,0.92 2.277,0.92 z m 21.808,-3.381 c 0,0.046 -0.023,0.368 -0.069,0.966 h -9.361 a 2.915,2.915 0 0 0 1.196,1.817 c 0.629,0.445 1.411,0.667 2.346,0.667 0.644,0 1.211,-0.092 1.702,-0.276 a 4.27,4.27 0 0 0 1.403,-0.92 l 1.909,2.07 c -1.165,1.334 -2.867,2.001 -5.106,2.001 -1.395,0 -2.63,-0.268 -3.703,-0.805 -1.073,-0.552 -1.901,-1.311 -2.484,-2.277 -0.583,-0.966 -0.874,-2.062 -0.874,-3.289 0,-1.211 0.284,-2.3 0.851,-3.266 a 6.039,6.039 0 0 1 2.369,-2.277 c 1.012,-0.552 2.139,-0.828 3.381,-0.828 1.211,0 2.308,0.26 3.289,0.782 a 5.616,5.616 0 0 1 2.3,2.254 c 0.567,0.966 0.851,2.093 0.851,3.381 z m -6.417,-3.703 c -0.813,0 -1.495,0.23 -2.047,0.69 -0.552,0.46 -0.889,1.089 -1.012,1.886 h 6.095 c -0.123,-0.782 -0.46,-1.403 -1.012,-1.863 -0.552,-0.475 -1.227,-0.713 -2.024,-0.713 z m 28.924,-7.222 V 95 h -3.427 v -1.426 c -0.89,1.073 -2.178,1.61 -3.864,1.61 -1.166,0 -2.224,-0.26 -3.174,-0.782 a 5.612,5.612 0 0 1 -2.208,-2.231 c -0.537,-0.966 -0.805,-2.085 -0.805,-3.358 0,-1.273 0.268,-2.392 0.805,-3.358 a 5.612,5.612 0 0 1 2.208,-2.231 c 0.95,-0.521 2.008,-0.782 3.174,-0.782 1.579,0 2.813,0.498 3.703,1.495 v -6.003 z m -6.67,14.306 c 0.904,0 1.656,-0.307 2.254,-0.92 0.598,-0.629 0.897,-1.464 0.897,-2.507 0,-1.043 -0.299,-1.87 -0.897,-2.484 -0.598,-0.629 -1.35,-0.943 -2.254,-0.943 -0.92,0 -1.679,0.314 -2.277,0.943 -0.598,0.613 -0.897,1.441 -0.897,2.484 0,1.043 0.299,1.878 0.897,2.507 0.598,0.613 1.357,0.92 2.277,0.92 z M 190.67,82.626 V 95 h -3.404 v -1.472 a 4.873,4.873 0 0 1 -1.702,1.242 5.48,5.48 0 0 1 -2.139,0.414 c -1.625,0 -2.913,-0.468 -3.864,-1.403 -0.95,-0.935 -1.426,-2.323 -1.426,-4.163 v -6.992 h 3.588 v 6.463 c 0,1.993 0.836,2.99 2.507,2.99 0.859,0 1.549,-0.276 2.07,-0.828 0.522,-0.567 0.782,-1.403 0.782,-2.507 V 82.626 Z M 84.475,111.259 a 4.003,4.003 0 0 1 1.725,-1.357 c 0.736,-0.307 1.579,-0.46 2.53,-0.46 v 3.312 a 12.683,12.683 0 0 0 -0.805,-0.046 c -1.028,0 -1.833,0.291 -2.415,0.874 -0.583,0.567 -0.874,1.426 -0.874,2.576 V 122 h -3.588 v -12.374 h 3.427 z m 18.897,4.6 c 0,0.046 -0.023,0.368 -0.069,0.966 h -9.361 a 2.916,2.916 0 0 0 1.196,1.817 c 0.628,0.445 1.41,0.667 2.346,0.667 0.644,0 1.211,-0.092 1.702,-0.276 a 4.277,4.277 0 0 0 1.403,-0.92 l 1.909,2.07 c -1.166,1.334 -2.868,2.001 -5.106,2.001 -1.396,0 -2.63,-0.268 -3.703,-0.805 -1.074,-0.552 -1.902,-1.311 -2.484,-2.277 -0.583,-0.966 -0.874,-2.062 -0.874,-3.289 0,-1.211 0.283,-2.3 0.85,-3.266 a 6.044,6.044 0 0 1 2.37,-2.277 c 1.012,-0.552 2.139,-0.828 3.38,-0.828 1.212,0 2.308,0.261 3.29,0.782 a 5.615,5.615 0 0 1 2.3,2.254 c 0.567,0.966 0.851,2.093 0.851,3.381 z m -6.417,-3.703 c -0.813,0 -1.495,0.23 -2.047,0.69 -0.552,0.46 -0.89,1.089 -1.012,1.886 h 6.095 c -0.123,-0.782 -0.46,-1.403 -1.012,-1.863 -0.552,-0.475 -1.227,-0.713 -2.024,-0.713 z m 13.468,10.028 c -1.027,0 -2.031,-0.123 -3.013,-0.368 -0.981,-0.261 -1.763,-0.583 -2.346,-0.966 l 1.196,-2.576 c 0.552,0.353 1.219,0.644 2.001,0.874 a 8.654,8.654 0 0 0 2.3,0.322 c 1.518,0 2.277,-0.376 2.277,-1.127 0,-0.353 -0.207,-0.606 -0.621,-0.759 -0.414,-0.153 -1.05,-0.284 -1.909,-0.391 -1.012,-0.153 -1.847,-0.33 -2.507,-0.529 a 3.97,3.97 0 0 1 -1.725,-1.058 c -0.475,-0.506 -0.713,-1.227 -0.713,-2.162 0,-0.782 0.223,-1.472 0.667,-2.07 0.46,-0.613 1.12,-1.089 1.978,-1.426 0.874,-0.337 1.902,-0.506 3.082,-0.506 0.874,0 1.741,0.1 2.599,0.299 0.874,0.184 1.595,0.445 2.162,0.782 l -1.196,2.553 a 7.142,7.142 0 0 0 -3.565,-0.92 c -0.766,0 -1.341,0.107 -1.725,0.322 -0.383,0.215 -0.575,0.491 -0.575,0.828 0,0.383 0.207,0.652 0.621,0.805 0.414,0.153 1.074,0.299 1.978,0.437 1.012,0.169 1.84,0.353 2.484,0.552 a 3.642,3.642 0 0 1 1.679,1.035 c 0.476,0.506 0.713,1.211 0.713,2.116 0,0.767 -0.23,1.449 -0.69,2.047 -0.46,0.598 -1.134,1.066 -2.024,1.403 -0.874,0.322 -1.916,0.483 -3.128,0.483 z m 16.537,-0.782 c -0.352,0.261 -0.789,0.46 -1.311,0.598 a 6.794,6.794 0 0 1 -1.61,0.184 c -1.472,0 -2.614,-0.376 -3.427,-1.127 -0.797,-0.751 -1.196,-1.855 -1.196,-3.312 v -5.083 h -1.909 v -2.76 h 1.909 v -3.013 h 3.588 v 3.013 h 3.082 v 2.76 h -3.082 v 5.037 c 0,0.521 0.131,0.928 0.391,1.219 0.276,0.276 0.66,0.414 1.15,0.414 0.568,0 1.051,-0.153 1.449,-0.46 z m 14.254,-5.543 c 0,0.046 -0.023,0.368 -0.069,0.966 h -9.361 a 2.911,2.911 0 0 0 1.196,1.817 c 0.628,0.445 1.41,0.667 2.346,0.667 0.644,0 1.211,-0.092 1.702,-0.276 a 4.276,4.276 0 0 0 1.403,-0.92 l 1.909,2.07 c -1.166,1.334 -2.868,2.001 -5.106,2.001 -1.396,0 -2.63,-0.268 -3.703,-0.805 -1.074,-0.552 -1.902,-1.311 -2.484,-2.277 -0.583,-0.966 -0.874,-2.062 -0.874,-3.289 0,-1.211 0.283,-2.3 0.851,-3.266 a 6.036,6.036 0 0 1 2.369,-2.277 c 1.012,-0.552 2.139,-0.828 3.381,-0.828 1.211,0 2.307,0.261 3.289,0.782 a 5.615,5.615 0 0 1 2.3,2.254 c 0.567,0.966 0.851,2.093 0.851,3.381 z m -6.417,-3.703 c -0.813,0 -1.495,0.23 -2.047,0.69 -0.552,0.46 -0.89,1.089 -1.012,1.886 h 6.095 c -0.123,-0.782 -0.46,-1.403 -1.012,-1.863 -0.552,-0.475 -1.227,-0.713 -2.024,-0.713 z m 15.859,-6.256 h 4.278 l -0.713,10.511 h -2.852 z m 2.139,16.284 c -0.629,0 -1.158,-0.199 -1.587,-0.598 a 2.037,2.037 0 0 1 -0.621,-1.495 c 0,-0.583 0.207,-1.066 0.621,-1.449 0.414,-0.399 0.943,-0.598 1.587,-0.598 0.644,0 1.173,0.199 1.587,0.598 0.414,0.383 0.621,0.866 0.621,1.449 0,0.583 -0.215,1.081 -0.644,1.495 -0.414,0.399 -0.935,0.598 -1.564,0.598 z"
                            fill="#14142b"
                            id="path119" />
                        <defs
                            id="defs142">
                            <linearGradient
                            id="paint0_linear"
                            x1="8.5950003"
                            y1="133.733"
                            x2="129.494"
                            y2="80.834999"
                            gradientUnits="userSpaceOnUse">
                            <stop
                                stop-color="#FB29CD"
                                id="stop121" />
                            <stop
                                offset=".653"
                                stop-color="#FDAD1F"
                                id="stop123" />
                            <stop
                                offset="1"
                                stop-color="#FDC51D"
                                id="stop125" />
                            </linearGradient>
                            <linearGradient
                            id="paint1_linear"
                            x1="7.0419998"
                            y1="303.33301"
                            x2="285.427"
                            y2="181.52699"
                            gradientUnits="userSpaceOnUse">
                            <stop
                                stop-color="#FB29CD"
                                id="stop128" />
                            <stop
                                offset=".653"
                                stop-color="#FDAD1F"
                                id="stop130" />
                            <stop
                                offset="1"
                                stop-color="#FDC51D"
                                id="stop132" />
                            </linearGradient>
                        </defs>
                        <rect
                            style="fill:#ffffff"
                            id="rect658"
                            width="110.88029"
                            height="35.985489"
                            x="110.20556"
                            y="39.808949" />
                        <rect
                            style="fill:#ffffff"
                            id="rect712"
                            width="78.043533"
                            height="39.359131"
                            x="47.230957"
                            y="38.684402" />
                        <text
                            xml:space="preserve"
                            style="font-size:24px;fill:#ffffff"
                            x="60.725521"
                            y="65.223694"
                            id="text768"><tspan
                            id="tspan766"
                            x="60.725521"
                            y="65.223694"
                            style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:24px;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold';fill:#000000"
                            dy="2.6500001"
                            rotate="0.1 0.1 0.1 0.1 0.1 0.1 0.1 0.1 0.1 0.1 0">Résifacile</tspan></text>
                        <rect
                            style="fill:#ffffff"
                            id="rect1005"
                            width="195.6711"
                            height="94.012093"
                            x="15.293833"
                            y="38.234581" />
                        <text
                            xml:space="preserve"
                            style="font-style:normal;font-variant:normal;font-weight:bold;font-stretch:normal;font-size:24px;font-family:sans-serif;-inkscape-font-specification:'sans-serif Bold';text-align:center;writing-mode:lr-tb;direction:ltr;text-anchor:middle;fill:#000000"
                            x="121.10986"
                            y="66.628784"
                            id="text1063"><tspan
                            id="tspan1061"
                            x="121.10986"
                            y="66.628784">&amp; Résifacile</tspan><tspan
                            x="121.10986"
                            y="96.628784"
                            id="tspan1065">s'occupe</tspan><tspan
                            x="121.10986"
                            y="126.62878"
                            id="tspan1067">du reste !</tspan></text>
                        </svg>

                    </div>
                </div>
            </div>
        </div>
        <div class="relative mx-auto max-w-screen-xl pt-20 pb-12 md:py-24 px-6 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-12">
            <livewire:search-brand/>
            <div>
                <div class="text-center md:text-left text-gray-400 md:text-xl leading-tight font-semibold pb-2">
                    Les <span class="text-gradient">catégories</span> de lettres de résiliations
                </div>
                <div class="text-center md:text-left text-xl md:text-3xl font-semibold leading-tight pb-6">
                    Nous avons ce qu'il vous faut
                </div>
                <div class="grid grid-cols-1 divide-gray-200 divide-y">
                    @foreach($categories as $categorie)
                        <a href="{{ route('frontend.template.edit', ['slug' => $categorie->slug]) }}" class="hover:bg-gray-50 hover:text-blue-700 py-2 flex justify-between w-full items-center">
                            <span>{{ $categorie->name }}</span>
                            <svg class="flex-none fill-blue-700" width="12" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.62 1.23a1.177 1.177 0 00-.89-.392c-.706 0-1.274.6-1.274 1.362 0 .381.15.728.397.993l7.497 7.898-7.497 7.874a1.475 1.475 0 00-.397.993c0 .762.568 1.363 1.275 1.363.353 0 .653-.139.889-.393l8.333-8.775c.3-.3.439-.67.45-1.074 0-.404-.15-.75-.45-1.062L2.62 1.23z"/></svg>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('pages.categories') }}" class="w-full text-center md:text-left inline-block text-blue-700 hover:text-blue-500 font-semibold pt-6">Voir toutes les catégories</a>
            </div>
            <div class="pt-6 md:pt-12">
                <div class="text-center md:text-left text-gray-400 md:text-xl leading-tight font-semibold pb-2">
                    Les <span class="text-gradient">types</span> de lettres de résiliations
                </div>
                <div class="text-center md:text-left text-xl md:text-3xl font-semibold leading-tight pb-6">
                    Les plus utilisés
                </div>
                <div class="grid grid-cols-1 divide-gray-200 divide-y">
                    @foreach($models as $model)
                        <a href="" class="hover:bg-gray-50 hover:text-blue-700 py-2 flex justify-between w-full items-center">
                            {{ $model->name }}
                            <svg class="flex-none fill-blue-700" width="12" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2.62 1.23a1.177 1.177 0 00-.89-.392c-.706 0-1.274.6-1.274 1.362 0 .381.15.728.397.993l7.497 7.898-7.497 7.874a1.475 1.475 0 00-.397.993c0 .762.568 1.363 1.275 1.363.353 0 .653-.139.889-.393l8.333-8.775c.3-.3.439-.67.45-1.074 0-.404-.15-.75-.45-1.062L2.62 1.23z"/></svg>
                        </a>
                    @endforeach
                </div>
                <a href="{{ route('pages.categories') }}" class="w-full text-center md:text-left inline-block text-blue-700 hover:text-blue-500 font-semibold pt-6">Voir toutes les modèles de lettres</a>
            </div>
        </div>
        <div class="bg-gradient-to-r from-[#fb29cd] to-[#fdc51d]">
            <div class="max-w-screen-lg mx-auto py-16 md:py-24 px-6 flex items-center flex-col relative z-[2]">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center w-full">
                    <div class="text-white text-4xl md:text-5xl leading-tight font-semibold">
                        Envoyez votre recommandé
                        <span class="block text-black">depuis chez vous !</span>
                    </div>
                    <div class="text-white text-5xl md:text-6xl leading-none text-left font-semibold pt-6 md:mt-0">
                        <small class="md:block text-black text-xl md:text-lg">à partir de</small>
                        2,22<span class="font-light">€<sup>*</sup></span>
                    </div>
                </div>
                <div class="pt-6 flex flex-col md:flex-row justify-between items-start md:items-center w-full gap-6 md:gap-12">
                    <div class="text-xl md:text-2xl text-white md:w-2/3">
                        <p>Et bénéficiez de notre service " Accès+ " offert pendant 15 jours puis 39,90€/mois*</p>
                        <ul class="text-sm font-semibold pt-6">
                            <li class="relative pl-6 flex items-center"><svg class="absolute left-0" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="#fff"></rect><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#2F64ED" stroke-width="2" stroke-linecap="round"></path></svg> Envoi de votre lettre de résiliation</li>
                            <li class="relative pl-6 flex items-center"><svg class="absolute left-0" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="#fff"></rect><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#2F64ED" stroke-width="2" stroke-linecap="round"></path></svg> Suivi de votre courrier</li>
                            <li class="relative pl-6 flex items-center"><svg class="absolute left-0" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="#fff"></rect><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#2F64ED" stroke-width="2" stroke-linecap="round"></path></svg> Un service support 7J/7</li>
                            <li class="relative pl-6 flex items-center"><svg class="absolute left-0" width="16" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="16" height="16" rx="8" fill="#fff"></rect><path d="M5.308 7.601L7.64 9.964l3.667-4.425" stroke="#2F64ED" stroke-width="2" stroke-linecap="round"></path></svg> Réception du courrier sous 3 jours ouvrés</li>
                        </ul>
                    </div>
                    <a href="{{ route('pages.trouvez-une-marque') }}" class="text-center w-full md:w-1/3 md:mt-12 h-16 px-12 bg-blue-700 text-white rounded-xl text-lg inline-flex justify-center items-center">J'en profite</a>
                </div>
                <div class="text-[11px] text-justify pt-6 md:pt-12 text-white">
                    *L’offre « accès+ » vous permet d'envoyer vos courriers depuis le site {{ config('app.name') }} en bénéficiant de prix réduit sur tout vos envois (-50%), l'accès à plus de 500 modèles de courrier et l'archivage de vos commandes. Cette offre tarifaire dite « accès+ » est valable uniquement dans le cas de la souscription d’un abonnement sans engagement, dont les quinze (15) premiers jours sont offerts, puis facturé à raison de trente-neuf euros et quatre-vingt-dix centimes (39,90€) tous les mois, conformément à nos conditions générales de vente, et résiliable à tout moment. Dans le cas où vous ne souhaiteriez pas vous abonner à notre service dit « accès+ », vous pouvez vous reporter sur nos offres unitaires. Le délais de rétractation expire quatorze (14) jours après le jour de la conclusion du contrat abonnement « accès+ ». Durant la période de quatorze (14) jours après l’exécution du contrat, vous disposez de votre droit de rétractation. Pour procéder à votre droit de rétractation, vous avez la possibilité de procéder à la résiliation à cette adresse :
                    {{ route('pages.se-desabonner') }}.
                </div>
            </div>
        </div>
        <div class="bg-white">
            <div class="max-w-screen-lg mx-auto py-12 md:py-24 px-6 flex flex-col gap-6 md:gap-12">
                <h1 class="text-3xl leading-tight md:text-5xl font-semibold w-full">{{ $page->title }}</h1>
                <article class="article w-full md:w-4/5">
                    {!! Markdown::parse($page->article) !!}
                </article>
            </div>
        </div>
        <div class="bg-gray-50">
            <div class="max-w-screen-xl mx-auto py-12 md:py-24 pb-12 px-6 flex flex-col gap-6 md:ap-12">
                <h2 class="text-4xl leading-tight md:text-5xl text-center font-semibold w-full">
                    Derniers articles
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-12 w-full">
                    @foreach ($guides as $guide)
                        <a
                            href="{{ route('guides.article', ['guide' => $guide]) }}"
                            class="flex flex-col rounded-xl shadow-lg overflow-hidden bg-white"
                        >
                            <?php /*
                            @if($guide->visual)
                                <img src="{{ Storage::disk('public')->url($guide->visual) }}" alt="{{ $guide->title }}" class="aspect-video object-cover">
                            @endif
                            */ ?>
                            <div class="p-6 flex-1">
                                <div class="text-lg md:text-xl md:leading-tight font-semibold mb-3">
                                    {{ $guide->title }}
                                </div>
                                <div class="text-sm leading-normal flex-none">
                                    {{ Str::words(strip_tags(Markdown::parse($guide->article)), 20, '…') }}
                                </div>
                            </div>
                            <div class="text-center p-6">
                                <div class="h-10 px-6 border-2 text-blue-700 rounded-full text-sm inline-flex items-center">
                                    Lire l'article
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="bg-white">
            <div class="max-w-screen-lg mx-auto px-6 py-12 md:py-24 flex flex-col md:flex-row justify-center items-center">
                <div class="w-full md:w-1/2 md:pr-6">
                    <img src="{{ asset('/images/call-center.jpg') }}"/>
                </div>
                <div class="w-full md:w-1/2 md:pl-6">
                    <h2 class="text-3xl md:text-5xl font-semibold leading-tight pt-5 md:pt-10 md:pt-0">Vous ne trouvez pas votre lettre ?</h2>
                    <p class="text-gradient text-xl font-semibold pt-3">Anaïs peut vous aider.</p>
                    <p class="pt-2"><strong>Une équipe en France</strong> est là pour assurer votre tranquillité, et que votre résiliation soit bien effectuée</p>
                    <a href="{{ route('pages.contact') }}" class="w-full md:w-auto mt-6 h-16 px-0 md:px-12 bg-blue-700 text-white rounded-xl text-lg inline-flex items-center justify-center md:justify-start">Demander de l'aide</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
