<?php

use App\Models\Campaign;
use App\Models\Page;
use App\Models\Transaction;
use Illuminate\Mail\Markdown;

Route::get('order', function () {
    return view('emails.orders.invoice', [
        'data' => Transaction::first(),
        'cgv' => Page::where('slug', 'cgv')->first(),
    ]);
});

Route::get('invoice', function () {
    return view('templates.invoice', [
        'data' => Transaction::first(),
        'number' => '123456789',
    ]);
});

Route::get('chargeback', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('emails.transactions.chargeback', [
        'transaction' => Transaction::first(),
    ]);
});

Route::get('campaign-executed', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('emails.campaign-executed', [
        'data' => Campaign::first(),
    ]);
});

Route::get('get-comment', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('emails.get-comment');
});

Route::get('proof-of-deposit', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('emails.proof-of-deposit');
});

Route::get('refund-confirmation', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('emails.refund-confirmation', [
        'transaction' => Transaction::first(),
    ]);
});

Route::get('unsubscribe-demande', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('emails.unsubscribe-demande', [
        'url' => 'https://www.google.com',
    ]);
});

Route::get('user-created', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('emails.user-created', [
        'url' => 'https://www.google.com',
    ]);
});

Route::get('welcome', function () {
    $markdown = new Markdown(view(), config('mail.markdown'));
    return $markdown->render('emails.welcome', [
        'data' => Transaction::first(),
    ]);
});
