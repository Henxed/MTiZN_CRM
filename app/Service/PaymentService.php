<?php

namespace App\Service;

use YooKassa\Client;

class PaymentService
{
    private function getClient(): Client
    {
        $client = new Client();
        $client->setAuth(config('services.youkassa.shop_id'), config('services.youkassa.secret_key'));

        return $client;
    }

    public function createPayment(float $amount, string $description, array $options = [])
    {
        $client = $this->getClient();
        $payment = $client->createPayment([
            'amount' => [
                'value' => $amount,
                'currency' => env('YOOKASSA_CURRENCY')
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => route('order', ['orderID' => $options['order_id'] ]),
            ],
            'capture' => false,
            'description' => $description,
            'metadata' => [
                'transaction_id' => $options['transaction_id']
            ]
        ],
        uniqid('', true));

        return $payment->getConfirmation()->getConfirmationUrl();
    }
}
