<?php

namespace App\Enums;

enum InvoiceType: string
{
    case SUBSCRIPTION = 'SUBSCRIPTION';

    case ORDER = 'ORDER';

    /**
     * @return string
     */
    public function label(): string
    {
        return match($this)
        {
            self::SUBSCRIPTION => 'Facture abonnement',
            self::ORDER => 'Facture commande',
        };
    }

    public function asHtml(): string
    {
        return match ($this) {
            self::SUBSCRIPTION => '<span class="inline-flex items-center whitespace-nowrap min-h-6 px-2 rounded-full text-xs font-semibold whitespace-nowrap flex items-center ml-0 mr-auto bg-gray-100 text-gray-500 dark:bg-gray-900 dark:text-gray-400">' . self::SUBSCRIPTION->label() . '</span>',
            self::ORDER => '<span class="inline-flex items-center whitespace-nowrap min-h-6 px-2 rounded-full text-xs font-semibold whitespace-nowrap flex items-center ml-0 mr-auto bg-green-100 text-green-600 dark:bg-green-400 dark:text-green-900">' . self::ORDER->label() . '</span>',
        };
    }

    /**
     * @return string
     */
    public function path(): string
    {
        return match($this)
        {
            self::SUBSCRIPTION => storage_path('/app/invoices/subscriptions'),
            self::ORDER => storage_path('/app/invoices/orders'),
        };
    }

    /**
     * @return string
     */
    public function relativePath(): string
    {
        return match($this)
        {
            self::SUBSCRIPTION => 'invoices/subscriptions',
            self::ORDER => 'invoices/orders',
        };
    }

    /**
     * @return string
     */
    public function mailInvoicePath(): string
    {
        return match($this)
        {
            self::SUBSCRIPTION => 'emails.subscriptions.invoice',
            self::ORDER => 'emails.orders.invoice',
        };
    }

    /**
     * @return string
     */
    public function template(): string
    {
        return match($this)
        {
            self::SUBSCRIPTION => 'templates.invoice-subscription',
            self::ORDER => 'templates.invoice',
        };
    }
}
