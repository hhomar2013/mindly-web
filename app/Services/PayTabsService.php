<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PayTabsService
{
    protected $baseUrl;
    protected $profileId;
    protected $serverKey;

    public function __construct()
    {
        $this->baseUrl   = config('services.paytabs.base_url');
        $this->profileId = config('services.paytabs.profile_id');
        $this->serverKey = config('services.paytabs.server_key');
    }

    public function createPayment($orderId, $amount, $description, $currency = "EGP")
    {
        $data = [
            "profile_id"       => $this->profileId,
            "tran_type"        => "sale",
            "tran_class"       => "ecom",
            "cart_id"          => $orderId,
            "cart_currency"    => $currency,
            "cart_amount"      => $amount,
            "cart_description" => $description,
            "callback"         => route('paytabs.callback'),
            "return"           => route('paytabs.return'),
        ];

        $response = Http::withHeaders([
            "authorization" => $this->serverKey,
            "content-type"  => "application/json",
        ])->post($this->baseUrl . "/payment/request", $data);

        return $response->json();
    }

    public function verifyPayment($tranRef)
    {
        $data = [
            "profile_id" => $this->profileId,
            "tran_ref"   => $tranRef,
        ];

        $response = Http::withHeaders([
            "authorization" => $this->serverKey,
            "content-type"  => "application/json",
        ])->post($this->baseUrl . "/payment/query", $data);

        return $response->json();
    }
}
