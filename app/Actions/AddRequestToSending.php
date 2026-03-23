<?php

namespace App\Actions;

use App\Contracts\PostLetter;
use App\DataTransferObjects\PostLetter\RequestData;
use App\Enums\SendingStatus;
use App\Models\Sending;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\App;

class AddRequestToSending
{
    /**
     * @param RequestData $requestData
     * @return void
     */
    public function handle(RequestData $requestData, Order $order): void
    {
        $sending = new Sending();
        $sending->status = SendingStatus::DRAFT;
        $sending->customer_id = $order->customer->id;
        $sending->order_id = $order->id;

        $data = App::make(PostLetter::class)->newSending();
        $data->requests[] = $requestData;

        $sending->data = $data;
        $sending->save();
    }
}
