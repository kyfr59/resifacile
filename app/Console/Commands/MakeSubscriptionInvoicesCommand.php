<?php

namespace App\Console\Commands;

use App\Actions\MakeNewInvoice;
use App\Enums\InvoiceType;
use App\Enums\TransactionStatus;
use App\Models\Transaction;
use Illuminate\Console\Command;

class MakeSubscriptionInvoicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génération des factures pour les abonnements de la veille';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $transactions = Transaction::where('transactionable_type', 'App\\Models\\Subscription')
            ->where('status', TransactionStatus::CAPTURED->value)
            ->doesntHave('invoice')->get();

        foreach ($transactions as $transaction) {
            (new MakeNewInvoice())->handle(
                transaction: $transaction,
                type: InvoiceType::SUBSCRIPTION,
            );
        }
    }
}
