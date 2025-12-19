<?php

namespace App\Traits;

use App\Contracts\Cart;
use App\Enums\AddressType;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

trait WithRecipients
{
    /**
     * @var array
     */
    public array $recipients = [];

    /**
     * @var bool[]
     */
    public array $showComplementRecipient = [];

    /**
     * @return void
     */
    public function initRecipients(): void
    {
        $cart = App::make(Cart::class);

        $recipients = $cart->getRecipients()->toArray();

        foreach ($recipients as $recipient) {
            if($recipient['address_line_2'] || $recipient['address_line_3'] || $recipient['address_line_5']) {
                $this->showComplementRecipient[] = true;
            } else {
                $this->showComplementRecipient[] = false;
            }

            $recipient['country'] .= '_' . $recipient['country_code'];
            $this->recipients[] = $recipient;
        }
    }

    /**
     * @return string[]
     */
    private function recipientsRules(): array
    {
        $recipients = [
            'recipients.*.address_line_2' => 'nullable|string|max:38',
            'recipients.*.address_line_3' => 'nullable|string|max:38',
            'recipients.*.address_line_4' => 'required|string|max:38',
            'recipients.*.address_line_5' => 'nullable|string|max:38',
            'recipients.*.postal_code' => 'required|string|size:5',
            'recipients.*.city' => 'required|string|max:32',
            'recipients.*.country' => 'required|in:FRANCE_FR',
            'recipients.*.type' => 'required',
        ];

        $this->validationAttributes = array_merge($this->validationAttributes, [
            'recipients.*.address_line_2' => '',
            'recipients.*.address_line_3' => '',
            'recipients.*.address_line_4' => 'adresse',
            'recipients.*.address_line_5' => '',
            'recipients.*.postal_code' => 'code postal',
            'recipients.*.city' => 'ville',
            'recipients.*.country' => 'pays',
        ]);

        $this->messages = array_merge($this->messages, [
            'recipients.*.country.in' => 'Nous sommes désolé, nous ne pouvons envoyer votre courrier vers cette destination, uniquement en France',
        ]);

        foreach ($this->recipients as $index => $recipient) {
            if($recipient['type'] === AddressType::PROFESSIONAL->value) {
                $recipients['recipients.'.$index.'.compagny'] = 'required|string|max:38';
                $this->validationAttributes['recipients.'.$index.'.compagny'] = 'raison social';
            } else {
                $recipients['recipients.'.$index.'.first_name'] = 'required|string|max:19';
                $this->validationAttributes['recipients.'.$index.'.first_name'] = 'prénom';
                $recipients['recipients.'.$index.'.last_name'] = 'required|string|max:19';
                $this->validationAttributes['recipients.'.$index.'.last_name'] = 'nom';
            }
        }

        return $recipients;
    }

    /**
     * @return void
     */
    public function updatedRecipients(): void
    {
        for ($i = 0; $i < count($this->recipients); $i++) {
            if($this->recipients[$i]['type'] === AddressType::PROFESSIONAL->value) {
                $this->recipients[$i]['first_name'] = null;
                $this->recipients[$i]['last_name'] = null;
            } else {
                $this->recipients[$i]['compagny'] = null;
            }
        }
    }

}
