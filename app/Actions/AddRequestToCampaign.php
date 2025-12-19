<?php

namespace App\Actions;

use App\Contracts\PostLetter;
use App\DataTransferObjects\PostLetter\RequestData;
use App\Enums\CampaignStatus;
use App\Models\Campaign;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\App;

class AddRequestToCampaign
{
    /**
     * @param RequestData $requestData
     * @return void
     */
    public function handle(RequestData $requestData, Order $order): void
    {
        $campaign = new Campaign();
        $campaign->status = CampaignStatus::PENDING_EXECUTION;
        $campaign->customer_id = $order->customer->id;
        $campaign->order_id = $order->id;

        $data = App::make(PostLetter::class)->newCampaign();
        $data->requests[] = $requestData;

        $campaign->data = $data;
        $campaign->save();
    }
}
