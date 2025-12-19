<?php

use App\Http\Controllers\Account\AuthenticateController;
use App\Http\Controllers\Account\HomePageController;
use App\Http\Controllers\Account\LoginController;
use App\Http\Controllers\SaleProcess\ImportController;
use App\Http\Controllers\SaleProcess\LetterController;
use App\Http\Controllers\SaleProcess\PaymentConfirmController;
use App\Http\Controllers\SaleProcess\PaymentController;
use App\Http\Controllers\SaleProcess\PostageController;
use App\Http\Controllers\SaleProcess\PreviewController;
use App\Http\Controllers\SaleProcess\RecipientController;
use App\Http\Controllers\SaleProcess\SenderController;
use App\Http\Controllers\SaleProcess\ValidationController;
use App\Http\Controllers\UnsubscribeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

//include_once __DIR__ . '/test.php';

/*
|--------------------------------------------------------------------------
| Redirects 301
|--------------------------------------------------------------------------
*/

Route::get('lettres', function() {
    return redirect(secure_url('categories'), 301);
});

Route::get('lettres/{slug}', function($slug) {
    return redirect(secure_url('lettre-resiliation', [$slug]), 301);
});

Route::get('categories/{slug}', function($slug) {
    return redirect(secure_url('lettre-resiliation', [$slug]), 301);
});

Route::get('marques', function() {
    return redirect(secure_url('trouvez-une-marque'), 301);
});

Route::get('marques/{slug}', function($slug) {
    return redirect(secure_url('lettre-resiliation', [$slug]), 301);
});

Route::get('mentions-legales-cgu-cgv', function() {
    return redirect(secure_url('mentions-legales'), 301);
});

Route::get('desabonnement', function() {
    return redirect(secure_url('se-desabonner'), 301);
});

Route::get('actualites', function() {
    return redirect(secure_url('guides'), 301);
});

Route::get('actualites/{slug}', function($slug) {
    return redirect(secure_url('guides', [$slug]), 301);
});

Route::get('login', function() {
    return redirect(secure_url('client/connexion'), 301);
});


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::post('/hipay/ip', function () {
    if(Request::has('ip') && (! Session::has('ipClient') || Session::get('ipClient') === null)) {
        Session::put('ipClient', Request::get('ip'));
    }
});

Route::get(
    '/resiliation',
    UnsubscribeController::class,
)
    ->name('frontend.resiliation');

/**
 *  Sale Process
 */

Route::middleware('verify.sale.process')
    ->group(function() {
        Route::get(
            '/lettre-resiliation/{slug}',
            [LetterController::class, 'edit'],
        )
            ->name('frontend.template.edit');

        Route::get(
            '/lettre/importation',
            ImportController::class,
        )
            ->name('frontend.letter.import');

        Route::get(
            '/lettre/destinataire',
            [RecipientController::class, 'show'],
        )
            ->name('frontend.letter.recipient');

        Route::get(
            '/lettre/expediteur',
            [SenderController::class, 'show'],
        )
            ->name('frontend.letter.sender');

        Route::get(
            '/lettre/affranchissement',
            [PostageController::class, 'show'],
        )
            ->name('frontend.letter.postage');

        Route::get(
            '/lettre/validation',
            ValidationController::class,
        )
            ->name('frontend.letter.validation');

        Route::get(
            '/lettre/paiement',
            PaymentController::class,
        )
            ->name('frontend.letter.payment');

        Route::get(
            '/lettre/paiement/confirmation',
            PaymentConfirmController::class,
        )
            ->name('frontend.letter.payment.confirmation');
    });

Route::get(
    '/lettre/preview/{id}',
    PreviewController::class,
)
    ->name('frontend.letter.preview');

/**
 * Account
 */
Route::get(
    '/login',
    fn () => view('compte.login'),
)
    ->name('login');

Route::get(
    '/authenticate',
    AuthenticateController::class,
)
    ->name('authenticate');

Route::post(
    '/login',
    LoginController::class,
);

Route::get('/mon-compte', HomePageController::class)
    ->middleware('auth:site')
    ->name('auth.account');

/**
 * Webhooks
 */
Route::webhooks('webhook-hipay', 'hipay');
Route::webhooks('webhook-mail', 'mail');
