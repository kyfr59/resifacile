<div class="w-full max-w-3xl">
    <div class="col-span-1 md:col-span-2 font-semibold pb-3">Mon abonnement</div>
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
    <div>
        <p class="text-sm font-semibold text-gray-800 mb-2">Statut de l'abonnement</p>
        <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-600 text-sm font-medium px-3 py-1 rounded-full">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
        Récurrent
        </span>
    </div>
    <div>
        <p class="text-sm font-semibold text-gray-800 mb-2">Désignation</p>
        <p class="text-sm text-gray-500">{{ $subscription->designation }}</p>
    </div>
    <div>
        <p class="text-sm font-semibold text-gray-800 mb-2">Montant</p>
        <p class="text-sm text-gray-500">49,90 €</p>
    </div>
    <div>
        <p class="text-sm font-semibold text-gray-800 mb-2">Créer le</p>
        <p class="text-sm text-gray-500">20/07/2026</p>
    </div>
    <div>
        <p class="text-sm font-semibold text-gray-800 mb-2">Prochaine échéance</p>
        <p class="text-sm text-gray-500">22/08/2026</p>
    </div>
    </div>
    <div class="flex justify-end mt-4">
    <button class="bg-red-400 hover:bg-red-500 transition-colors text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow-sm">
        Me désabonner
    </button>
</div>