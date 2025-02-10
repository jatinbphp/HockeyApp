<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Child;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Fee;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonInterval;

class CommonController extends Controller
{
    public function changeStatus(Request $request){
        $updateInput = DB::table($request['table_name'])->where('id', $request['id'])->first();
        $updateInput->status = ($request['type'] == 'unassign') ? 'inactive' : 'active';
        DB::table($request['table_name'])->where('id', $request['id'])->update((array) $updateInput);
    }

    public function changeSkillStatus(Request $request){
        $updateInput = DB::table($request['table_name'])->where('id', $request['id'])->first();
        $updateInput->status = ($request['type'] == 'off') ? 'off' : 'on';
        DB::table($request['table_name'])->where('id', $request['id'])->update((array) $updateInput);
    }

    public function page_not_found(){
        $data['menu'] = '404';
        return view('admin.errors.404', $data);
    }   

    public function changePaymentStatus(Request $request){

        $payment = Payment::select('id','status','payment_date')->where('child_id', $request['id'])->orderBy('payment_date', 'desc')->first();
        $childId =  $request['id'];
        $parentId =  $request['parentid'];
        $fees = Fee::pluck('fees')->first();
        $fee = (isset($fees)) ? $fees : '';
        $amount = str_replace(',', '', $fee);
        $amount = floatval($amount);
        $formattedAmount = number_format($amount, 2, '.', '');

        $paymentStatusTmp = "pending";
        if($payment){

            $paymentStatus = $payment->status ?? 'pending';
            $paymentDate = $payment->payment_date ? Carbon::parse($payment->payment_date) : null;
            $todayDate = Carbon::now();

            // Check for 1-year renewal period
            $oneYearRenewal = $paymentDate && $paymentDate->diffInYears($todayDate) >= 1;

            if($paymentStatus == 'pending'){
                $paymentStatusTmp = "pending";
            }else if($paymentStatus == 'Paid'){
                
                if($oneYearRenewal){
                    $paymentStatusTmp = "Expired";
                }else{
                    $paymentStatusTmp = "Paid";
                }

            }else{
                $paymentStatusTmp = "pending";
            }
        } 
              
        // PAYMENT STATUS UPDATE MANULLY FROM ADMIN REQUESTED..
        if($paymentStatusTmp == "pending"){

            $transactionId = mt_rand(1000000000, 9999999999);

            $payment = Payment::create([
                'child_id' => $childId,
                'parent_id' => $parentId,
                'transaction_id' => $transactionId,
                'amount' => $formattedAmount,
                'payment_date' => date('Y-m-d'),
                'status' => 'Paid',
                'response' => ''
            ]);

        }else{

            if($payment){
                Payment::where('id', $payment->id)->update([                  
                    'status' => 'pending'              
                ]);
            }

        }

    }
    
}
