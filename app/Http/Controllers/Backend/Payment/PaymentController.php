<?php

namespace App\Http\Controllers\Backend\Payment;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment.form');
    }

    public function processPayment(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // payment processing and change status randomly
        $transactionId = 'TXN' . uniqid();

        $status = $this->paymentStatus();

        //payment data
        $data = [
            'user_id' => auth()->user()->id,
            'transaction_id' => $transactionId,
            'amount' => $request->amount,
            'status' => $status,
        ];

        // Save payment information
        Payment::create($data);

        // Redirect based on the status
        if ($status === 'success') {
            return redirect()->route('payment.success', ['transaction_id' => $transactionId]);
        } else {
            return redirect()->route('payment.failure', ['transaction_id' => $transactionId]);
        }
    }

    private function paymentStatus()
    {
        // random success or failure
        return rand(0, 1) ? 'success' : 'failure';
    }

    public function success($transactionId)
    {
        if(empty($transactionId)){
            return redirect()->route('payment.form');
        }
        // Update the status to success for the given transaction ID
        Payment::where('transaction_id', $transactionId)->update(['status' => 'success']);

        return view('payment.success',compact('transactionId'));
    }

    public function failure($transactionId)
    {
        if(empty($transactionId)){
            return redirect()->route('payment.form');
        }

        // Update the status to failure for the given transaction ID
        Payment::where('transaction_id', $transactionId)->update(['status' => 'failure']);

        return view('payment.failure',compact('transactionId'));
    }
}
