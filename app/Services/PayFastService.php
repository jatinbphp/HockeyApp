<?php

namespace App\Services;

use GuzzleHttp\Client;

class PayFastService
{
    protected $client;
    protected $merchantId;
    protected $merchantKey;
    protected $passphrase;
    protected $testMode;

    public function __construct()
    {
        $this->client = new Client();
        $this->merchantId = config('payfast.merchant_id');
        $this->merchantKey = config('payfast.merchant_key');
        $this->passphrase = config('payfast.passphrase');
        $this->testMode = config('payfast.test_mode');
    }

    public function createPayment($orderId, $amount, $item_name, $item_description)
    {
        $data = [
            'merchant_id' => $this->merchantId,
            'merchant_key' => $this->merchantKey,
            'return_url' => route('payfast.return'),
            'cancel_url' => route('payfast.cancel'),
            'notify_url' => route('payfast.notify'),
            'name_first' => 'First Name',
            'name_last' => 'Last Name',
            'email_address' => 'email@example.com',
            'm_payment_id' => $orderId,
            'amount' => number_format($amount, 2, '.', ''),
            'item_name' => $item_name,
            'item_description' => $item_description,
            'passphrase' => $this->passphrase,
        ];

        if ($this->testMode) {
            $url = 'https://sandbox.payfast.co.za/eng/process';
        } else {
            $url = 'https://www.payfast.co.za/eng/process';
        }

        return $url . '?' . http_build_query($data);
    }
}