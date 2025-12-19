<?php

namespace App\Traits;

use App\Contracts\Cart;
use App\Enums\AddressType;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait WithSenders
{
    /**
     * @var array
     */
    public array $senders = [];

    /**
     * @var bool[]
     */
    public array $showComplementSender = [];

    /**
     * @return void
     */
    public function initSenders(): void
    {
        $cart = App::make(Cart::class);

        $senders = $cart->getSenders()->toArray();

        foreach ($senders as $sender) {
            if($sender['address_line_2'] || $sender['address_line_3'] || $sender['address_line_5']) {
                $this->showComplementSender[] = true;
            } else {
                $this->showComplementSender[] = false;
            }

            $sender['type'] = $sender['type'];
            $sender['country'] .= '_' . $sender['country_code'];
            $this->senders[] = $sender;
        }
    }

    /**
     * @return string[]
     */
    private function sendersRules(): array
    {
        $senders = [
            'senders.*.address_line_2' => 'nullable|string|max:38',
            'senders.*.address_line_3' => 'nullable|string|max:38',
            'senders.*.address_line_4' => 'required|string|max:38',
            'senders.*.address_line_5' => 'nullable|string|max:38',
            'senders.*.postal_code' => 'required|string|size:5',
            'senders.*.city' => 'required|string|max:32',
            'senders.*.country' => 'required|in:FRANCE_FR',
            'senders.*.type' => 'required',
        ];

        $this->validationAttributes = array_merge($this->validationAttributes, [
            'senders.*.address_line_2' => '',
            'senders.*.address_line_3' => '',
            'senders.*.address_line_4' => 'adresse',
            'senders.*.address_line_5' => '',
            'senders.*.postal_code' => 'code postal',
            'senders.*.city' => 'ville',
            'senders.*.country' => 'pays',
        ]);

        $this->messages = array_merge($this->messages, [
            'senders.*.country.in' => 'Nous sommes désolé, l\'expéditeur doit être une adresse française',
        ]);

        foreach ($this->senders as $index => $recipient) {
            if($recipient['type'] === AddressType::PROFESSIONAL->value) {
                $senders['senders.'.$index.'.compagny'] = 'required|string|max:38';
                $this->validationAttributes['senders.'.$index.'.compagny'] = 'raison social';
            }

            $senders['senders.'.$index.'.first_name'] = 'required|string|max:19';
            $this->validationAttributes['senders.'.$index.'.first_name'] = 'prénom';
            $senders['senders.'.$index.'.last_name'] = 'required|string|max:19';
            $this->validationAttributes['senders.'.$index.'.last_name'] = 'nom';
        }

        return $senders;
    }

    /**
     * @return void
     */
    public function updatedSenders(): void
    {
        if(key_exists('first_name', $this->senders[0]) && key_exists('last_name', $this->senders[0])) {
            $this->name = Str::of($this->senders[0]['first_name'] . ' ' . $this->senders[0]['last_name'])
                ->lower()
                ->title();
        }

        if($this->senders[0]['type'] == AddressType::PROFESSIONAL->value) {
            if(key_exists('compagny', $this->senders[0])) {
                $this->compagny = $this->senders[0]['compagny'];
            }
        } else {
            $this->compagny = null;
            $this->senders[0]['compagny'] = null;
        }

        $this->from_city = $this->senders[0]['city'];
    }

}
