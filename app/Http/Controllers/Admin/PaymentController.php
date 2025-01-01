<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\User;
use App\Models\Child;

class PaymentController extends Controller
{
    public function paymentReturn(Request $request){
        Log::channel('payment')->info('This is a custom log message.');
    }

    public function paymentCancel(Request $request){        
      
    }

    public function paymentNotify(Request $request){ 

        Log::info('PayFast Notify URL called:', $request->all());
        
        // Ensure the correct response header
        header('HTTP/1.0 200 OK');
        flush();

        // Define if using sandbox or live mode
        $sandboxMode = env('PAYFAST_TEST_MODE');
        $pfHost = $sandboxMode ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';

        $paymentData = $request->all();
        $paymentResponse = json_encode($paymentData);

        Log::channel('payment')->info($paymentResponse);

        if(isset($paymentData['payment_status']) && !empty($paymentData['payment_status']) && $paymentData['payment_status'] == 'COMPLETE'){

            $cartTotal = (isset($paymentData['amount_gross'])) ? $paymentData['amount_gross'] : 0;

            $pfData = $paymentData;
            // Strip any slashes in data
            foreach ($pfData as $key => $val) {
                $pfData[$key] = stripslashes($val);
            }

            // Convert posted variables to a string excluding the signature
            $pfParamString = '';
            foreach ($pfData as $key => $val) {
                if ($key !== 'signature') {
                    $pfParamString .= $key . '=' . urlencode($val) . '&';
                } else {
                    break;
                }
            }

            $pfParamString = substr($pfParamString, 0, -1);
            try {
                    $checkValidIP = $this->pfValidIP();
                    $checkValidPaymentData = $this->pfValidPaymentData($cartTotal, $pfData);
                    $checkServerConfirmation = $this->pfValidServerConfirmation($pfParamString, $pfHost); 

                    if(isset($paymentData['custom_str2']) && !empty($paymentData['custom_str2']) && $paymentData['custom_str2'] == "MULITPLE_PAYMENT"){

                        if ($checkValidIP && $checkValidPaymentData && $checkServerConfirmation) {
                            // if(isset($paymentData['custom_int1']) && !empty($paymentData['custom_int1'])){
                               
                                $paidAmt = (isset($paymentData['amount_gross'])) ? $paymentData['amount_gross'] : 0;
                                $transactionId = (isset($paymentData['pf_payment_id'])) ? $paymentData['pf_payment_id'] : 0;
    
                                if($paidAmt != 0 && $transactionId != 0){
                                   
                                    $paymentDt = Carbon::now()->format('Y-m-d H:i:s');
                                    $childIds = explode(',', $paymentData['custom_str1']); // Split custom_int1 into an array of child IDs
                                    $individualAmt = $paidAmt / count($childIds); // Calculate amount per child
    
                                    Log::channel('payment')->info('Paid amount and transaction ID', [
                                        'paidAmt' => $paidAmt,
                                        'transactionId' => $transactionId
                                    ]);
    
                                    foreach ($childIds as $childId) {
                                        $payment = Payment::create([
                                            'child_id' => $childId,
                                            'parent_id' => $paymentData['custom_int2'],
                                            'transaction_id' => $transactionId,
                                            'amount' => $individualAmt,
                                            'payment_date' => date('Y-m-d'),
                                            'status' => 'Paid',
                                            'response' => $paymentResponse
                                        ]);
                                    }

                                    /* UPDATE STATUS ACTIVE IF NEW SIGNUP FROM THIS API */
                                    if(!empty($paymentData['custom_int2']) && $paymentData['custom_int2'] > 0){
    
                                        User::where('id', $paymentData['custom_int2'])
                                        ->update([
                                            'status' => 'active',
                                        ]);  
    
                                    }
    
                                     // Log successful payment
                                    Log::channel('payment')->info('Payment successful', [
                                        'child_ids' => $childIds,
                                        'transaction_id' => $transactionId,
                                        'amount' => $paidAmt,
                                        'status' => 'Paid'
                                    ]);
                                  
                                  
                                }
                            // }
    
                            return response('Payment notification received', 200);
    
                        } else{
                            return response('Payment notification received', 200);
                        }

                    }else{

                        if ($checkValidIP && $checkValidPaymentData && $checkServerConfirmation) {
                            if(isset($paymentData['custom_int1']) && !empty($paymentData['custom_int1'])){
                               
                                $paidAmt = (isset($paymentData['amount_gross'])) ? $paymentData['amount_gross'] : 0;
                                $transactionId = (isset($paymentData['pf_payment_id'])) ? $paymentData['pf_payment_id'] : 0;
    
                                if($paidAmt != 0 && $transactionId != 0){
                                   
                                    $paymentDt = Carbon::now()->format('Y-m-d H:i:s');
    
    
                                    Log::channel('payment')->info('Paid amount and transaction ID', [
                                        'paidAmt' => $paidAmt,
                                        'transactionId' => $transactionId
                                    ]);
    
                                    $payment = Payment::create([
                                        'child_id' => $paymentData['custom_int1'],
                                        'parent_id' => $paymentData['custom_int2'],
                                        'transaction_id' => $transactionId,
                                        'amount' => $paidAmt,
                                        'payment_date' => date('Y-m-d'),
                                        'status' => 'Paid',
                                        'response' => $paymentResponse
                                    ]);
                                    
                                    /* UPDATE STATUS ACTIVE IF NEW SIGNUP FROM THIS API */
                                    if(!empty($paymentData['custom_int2']) && $paymentData['custom_int2'] > 0){
    
                                        User::where('id', $paymentData['custom_int2'])
                                        ->update([
                                            'status' => 'active',
                                        ]);  
    
                                    }
    
                                     // Log successful payment
                                    Log::channel('payment')->info('Payment successful', [
                                        'child_id' => $paymentData['custom_int1'],
                                        'transaction_id' => $transactionId,
                                        'amount' => $paidAmt,
                                        'status' => 'Paid'
                                    ]);
                                  
                                  
                                }
                            }
    
                            return response('Payment notification received', 200);
    
                        } else{
                            return response('Payment notification received', 200);
                        }

                    }
                  
                    

            } catch (\Exception $e) {
                Log::channel('payment')->error('Payment processing failed', [
                    'error' => $e->getMessage(),
                    'stack' => $e->getTraceAsString()
                ]);

                return response('Payment notification received', 200);
            }
        }

        return response('Payment notification received', 200);
    }


    public function pfValidIP(): bool
    {
        // Valid PayFast hosts
        $validHosts = [
            'www.payfast.co.za',
            'sandbox.payfast.co.za',
            'w1w.payfast.co.za',
            'w2w.payfast.co.za',
        ];

        $validIps = [];

        // Get IP addresses for each valid host
        foreach ($validHosts as $pfHostname) {
            $ips = gethostbynamel($pfHostname);
            if ($ips !== false) {
                $validIps = array_merge($validIps, $ips);
            }
        }

        // Remove duplicates
        $validIps = array_unique($validIps);

        // Get the referrer IP address
        $referrerIp = gethostbyname(parse_url(request()->server('HTTP_REFERER'), PHP_URL_HOST));

        // Check if the referrer IP is in the list of valid IPs
        return in_array($referrerIp, $validIps, true);
    }

    public function pfValidPaymentData(float $cartTotal, array $pfData): bool
    {
        return !(abs($cartTotal - (float)$pfData['amount_gross']) > 0.01);
    }

    public function pfValidServerConfirmation(string $pfParamString, string $pfHost = 'sandbox.payfast.co.za', string $pfProxy = null): bool
    {
        // Use cURL to validate server confirmation
        if (in_array('curl', get_loaded_extensions(), true)) {
            // Variable initialization
            $url = 'https://' . $pfHost . '/eng/query/validate';

            // Create default cURL object
            $ch = curl_init();

            // Set cURL options - Use curl_setopt for greater PHP compatibility
            // Base settings
            curl_setopt($ch, CURLOPT_USERAGENT, null);  // Set user agent
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return output as string rather than outputting it
            curl_setopt($ch, CURLOPT_HEADER, false); // Don't include header in output
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            // Standard settings
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $pfParamString);
            if (!empty($pfProxy)) {
                curl_setopt($ch, CURLOPT_PROXY, $pfProxy);
            }

            // Execute cURL
            $response = curl_exec($ch);
            curl_close($ch);
            if ($response === 'VALID') {
                return true;
            }
        }

        return false;
    }

    public function index(Request $request){
        $data['menu'] = 'Payments';

        $start_date = $request->start_date; // Retrieve start_date from the request
        $end_date = $request->end_date;     // Retrieve end_date from the request

        // Validate date inputs (optional)
        if ($start_date) {
            $start_date = Carbon::parse($start_date)->startOfDay(); // Ensure start of the day
        }
        if ($end_date) {
            $end_date = Carbon::parse($end_date)->endOfDay(); // Ensure end of the day
        }

        // Fetch payments with children and apply filters
        $payments = Payment::with(['children' => function ($query) {
            $query->where('status', 'active')->whereNull('deleted_at'); // Filter active children
        }])
        ->whereHas('children', function ($query) {
            $query->where('status', 'active')->whereNull('deleted_at'); // Ensure payments have active children
        })
        ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]); // Apply date range filter
        })
        ->orderBy('created_at', 'DESC') // Order by created_at
        ->get();

        if ($request->ajax()) {
            return Datatables::of($payments)
                ->addIndexColumn()
                ->editColumn('fullname', function ($row) {
                    // Safely handle the relationship
                    return ucfirst(optional($row->children)->fullname ?? 'N/A');
                })
                ->editColumn('transaction_id', function ($row) {
                    // Return transaction ID or N/A
                    return $row->transaction_id ?? 'N/A';
                })
                ->editColumn('amount', function ($row) {
                    // Format amount for better readability
                    return number_format($row->amount, 2);
                })
                ->editColumn('created_at', function ($row) {
                    // Format payment_date
                    return Carbon::parse($row->created_at)->format('Y-m-d');
                })
                ->editColumn('status', function ($row) {
                    // Capitalize status
                    return ucfirst($row->status ?? 'N/A');
                })
                ->make(true);
        }
        return view('admin.payments.index', $data);
    }


}
