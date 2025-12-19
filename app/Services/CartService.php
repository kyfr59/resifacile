<?php

namespace App\Services;

use App\Contracts\Cart;
use App\DataTransferObjects\AddressData;
use App\DataTransferObjects\CustomerData;
use App\DataTransferObjects\DocumentData;
use App\DataTransferObjects\FoldData;
use App\DataTransferObjects\OrderData;
use App\DataTransferObjects\PostLetter\AddressLinesData;
use App\DataTransferObjects\PostLetter\ContentData;
use App\DataTransferObjects\PostLetter\DocumentData as PostLetterDocumentData;
use App\DataTransferObjects\PostLetter\FoldData as PostLetterFoldData;
use App\DataTransferObjects\PostLetter\NotificationData;
use App\DataTransferObjects\PostLetter\OptionData;
use App\DataTransferObjects\PostLetter\PaperAddress;
use App\DataTransferObjects\PostLetter\ProtocolData;
use App\DataTransferObjects\PostLetter\ProtocolFtpData;
use App\DataTransferObjects\PostLetter\RecipientData;
use App\DataTransferObjects\PostLetter\RequestData;
use App\DataTransferObjects\PostLetter\SenderData;
use App\Enums\AddressType;
use App\Enums\MediaType;
use App\Enums\NotificationFormat;
use App\Enums\NotificationType;
use App\Enums\PostageType;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

final class CartService implements Cart
{
    public function __construct()
    {
        if(!Session::has('cart')) {
            Session::put('cart', [
                'id' => Str::uuid(),
                'product' => null,
                'documents' => DocumentData::collection([]),
                'senders' => AddressData::collection([AddressData::empty(['type' => AddressType::PERSONAL])]),
                'recipients' => AddressData::collection([AddressData::empty(['type' => AddressType::PROFESSIONAL])]),
                'order' => OrderData::empty(),
            ]);
        }

        if(!Session::has('customer')) {
            Session::put('customer', CustomerData::empty());
        }
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return Session::get('cart.id');
    }

    /**
     * @return void
     */
    public function destroy(): void
    {
        Session::forget('cart');
        Session::forget('customer');

        new self();
    }

    /**
     * @param string $slug
     * @return void
     */
    public function addProduct(string $slug): void
    {
        $cart = Session::get('cart');
        $cart['product'] = $slug;

        Session::put('cart', $cart);
    }

    /**
     * @return string|null
     */
    public function getProduct(): ?string
    {
        return Session::get('cart.product');
    }

    /**
     * @param OrderData $order
     * @return void
     */
    public function addOrder(OrderData $order): void
    {
        $cart = Session::get('cart');
        $cart['order'] = $order->toArray();

        Session::put('cart', $cart);
    }

    /**
     * @return OrderData
     */
    public function getOrder(): OrderData
    {
        return OrderData::from(Session::get('cart.order'));
    }

    /**
     * @param array $documents
     * @return void
     */
    public function addDocuments(array $documents): void
    {
        $cart = Session::get('cart');

        foreach ($documents as $document) {
            $cart['documents'][] = $document;
        }

        Session::put('cart', $cart);
    }

    /**
     * @param array $documents
     * @return void
     */
    public function updateAllDocuments(array $documents): void
    {
        $cart = Session::get('cart');

        $cart['documents'] = DocumentData::collection($documents);

        Session::put('cart', $cart);
    }

    /**
     * @return DataCollection
     */
    public function getDocuments(): DataCollection
    {
        return Session::get('cart')['documents'];
    }

    public function getDocument(int $id): DocumentData
    {
        return Session::get('cart')['documents'][$id];
    }

    public function removeDocument(int $id): void
    {
        unset(Session::get('cart')['documents'][$id]);
    }

    /**
     * @return FoldData
     */
    public function getFold(): FoldData
    {
        return FoldData::fromArray(Arr::except(Session::get('cart'), ['product', 'order']));
    }

    /**
     * @param array $addresses
     * @return void
     */
    public function addRecipients(array $addresses): void
    {
        $cart = Session::get('cart');
        $cart['recipients'] = AddressData::collection($addresses);
        Session::put('cart', $cart);
    }

    /**
     * @param int $index
     * @return void
     */
    public function removeRecipient(int $index): void
    {
        unset(Session::get('cart')['recipients'][$index]);
    }

    /**
     * @return DataCollection
     */
    public function getRecipients(): DataCollection
    {
        return Session::get('cart')['recipients'];
    }

    /**
     * @param array $addresses
     * @return void
     */
    public function addSenders(array $addresses): void
    {
        $cart = Session::get('cart');
        $cart['senders'] = AddressData::collection($addresses);
        Session::put('cart', $cart);
    }

    /**
     * @param int $index
     * @return void
     */
    public function removeSender(int $index): void
    {
        unset(Session::get('cart')['senders'][$index]);
    }

    /**
     * @return DataCollection
     */
    public function getSenders(): DataCollection
    {
        return Session::get('cart')['senders'];
    }

    /**
     * @param CustomerData $customerData
     * @return void
     */
    public function addCustomer(CustomerData $customerData): void
    {
        Session::put('customer', $customerData->toArray());
    }

    /**
     * @return CustomerData
     */
    public function getCustomer(): CustomerData
    {
        return CustomerData::from(Session::get('customer'));
    }

    /**
     * @param PostageType $postageType
     * @return void
     */
    public function addPostageType(PostageType $postageType): void
    {
        $cart = Session::get('cart');
        $cart['order']['postage'] = $postageType;
        Session::put('cart', $cart);
    }

    /**
     * @return PostageType|null
     */
    public function getPostageType(): PostageType|null
    {
        return PostageType::from(Session::get('cart.order.postage'));
    }
}
