<?php

namespace App\Http\Controllers;

use App\Services\PayTabsService;
use Illuminate\Http\Request;

class PayTabsController extends Controller
{
    protected $paytabs;

    public function __construct(PayTabsService $paytabs)
    {
        $this->paytabs = $paytabs;
    }

    public function checkout()
    {
        $orderId = "ORDER-" . time(); // Example order id
        $payment = $this->paytabs->createPayment($orderId, 500, "Test Order");

        if (isset($payment['redirect_url'])) {
            return redirect($payment['redirect_url']);
        }

        return response()->json($payment);
    }

    public function callback(Request $request)
    {
        // Webhook: PayTabs sends notification here
        $tranRef = $request->input('tran_ref');
        $verify  = $this->paytabs->verifyPayment($tranRef);

        // TODO: Handle order update
        return response()->json($verify);
    }

    public function return(Request $request)
    {
        // Customer is redirected here after payment
        $tranRef = $request->input('tranRef');
        $verify  = $this->paytabs->verifyPayment($tranRef);

        if (($verify['payment_result']['response_status'] ?? '') === "A") {
            return "✅ Payment Successful!";
        }

        return "❌ Payment Failed!";
    }
}
