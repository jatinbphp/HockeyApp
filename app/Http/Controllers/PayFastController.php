<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayFastService;
use App\Models\Payment;

class PayFastController extends Controller
{
    protected $payFastService;

    public function __construct(PayFastService $payFastService)
    {
        $this->payFastService = $payFastService;
    }

    public function createPayment(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|string',
            'amount' => 'required|numeric',
            'item_name' => 'required|string',
            'item_description' => 'nullable|string',
        ]);

        $validated['order_id'] = uniqid();
        $paymentUrl = $this->payFastService->createPayment(
            $validated['order_id'],
            $validated['amount'],
            $validated['item_name'],
            $validated['item_description']
        );

        // Store the payment request in the database
        Payment::create([
            'order_id' => $validated['order_id'],
            'amount' => $validated['amount'],
            'item_name' => $validated['item_name'],
            'item_description' => $validated['item_description'],
            'status' => 'pending',
        ]);

        return response()->json(['payment_url' => $paymentUrl]);
    }

    public function notify(Request $request)
    {
        // Handle PayFast IPN (Instant Payment Notification)
        // Validate and process the payment

        $data = $request->all();
        
        // Verify IPN data here
        
        $payment = Payment::where('order_id', $data['m_payment_id'])->first();
        
        if ($payment) {
            // Update payment status
            $payment->status = 'completed';
            $payment->save();
        }

        return response()->json(['message' => 'Notification received']);
    }

    public function return(Request $request)
    {

        // Handle the return URL
        $orderId = $request->query('m_payment_id');

        $payment = Payment::where('order_id', $orderId)->first();
        
        if ($payment) {
            // Update payment status
            $payment->status = 'completed';
            $payment->save();
        }

        // Handle the return URL
        return response()->json(['message' => 'Payment successful!']);
    }

    public function cancel(Request $request)
    {
        $orderId = $request->query('m_payment_id');

        $payment = Payment::where('order_id', $orderId)->first();
        
        if ($payment) {
            // Update payment status
            $payment->status = 'cancelled';
            $payment->save();
        }
        
        // Handle the cancel URL
        return response()->json(['message' => 'Payment cancelled!']);
    }
}