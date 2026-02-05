<?php

namespace App\Http\Controllers;

use App\Mail\TestEmail;
use Exception;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Modules\Bank\Entities\Bank;
use Modules\WalkIn\Entities\Passport;
use Modules\WalkIn\Entities\Payment;
use Modules\WalkIn\Entities\WalkinAppInfo;


class SslCommerzPaymentController extends Controller
{
    //
    //    public function exampleEasyCheckout()
    //    {
    //        return view('exampleEasycheckout');
    //    }

    public function exampleHostedCheckout($id)
    {
        $data = WalkinAppInfo::find($id);
        $purchases = \Illuminate\Support\Facades\DB::table('walkin_app_details')
            ->join('checklists', 'checklists.id', '=', 'walkin_app_details.c_visa_category')
            ->where('walkin_app_details.walkin_app_info_id', $data->id)
            ->sum('checklists.price');
        if ($data->group_price > 0) {
            $val        = ((($data->checklist->price - $data->checklist->service_charge) + $data->group_price)  + $purchases);
        } else {
            $val        = ($data->checklist->price + $purchases);
        }

        return view('exampleHosted', compact('data', 'val'));
        //        return view('exampleHosted');
    }

    public function hostedCheckout($id)
    {
        $data = WalkinAppInfo::with('checklist')->find($id);
        if (!$data) {
            return response()->json([
                'success' => true,
                'message' => 'Application not found',
                'data' => [],
            ]);
        }
        $purchases = DB::table('walkin_app_details')
            ->join('checklists', 'checklists.id', '=', 'walkin_app_details.c_visa_category')
            ->where('walkin_app_details.walkin_app_info_id', $data->id)
            ->sum('checklists.price');
        if ($data->group_price > 0) {
            $val        = ((($data->checklist->price - $data->checklist->service_charge) + $data->group_price)  + $purchases);
        } else {
            $val        = ($data->checklist->price + $purchases);
        }
        $passportNo = Passport::where('walkin_app_info_id', $data->id)->value('passport_no');
        $responseData = [
            'amount' => $val,
            'application_id' => $data->id,
            'customer_name' => $data->p_name,
            'customer_mobile' => $data->phone,
            'customer_email' => $data->email,
            'address' => $data->information,
            'destination' => $data->checklist->visaType->visa_type,
            'apply_date' => $data->date,
            'passport_number' => $passportNo,
            'visa_type' => $data->checklist->title,
            'visa_validity' => '',
            'visa_fee' => $val,
            'uniqueKey' => $data->uniqueKey,
        ];
        return response()->json([
            'success' => true,
            'data' => $responseData,
            'message' => 'Success',
        ]);
    }

    public function pay(Request $request)
    {
        $post_data = array();
        $post_data['total_amount'] = $request->amount; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        $post_data['application_id'] = $request->application_id; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $request->customer_name;
        $post_data['cus_email'] = $request->customer_email;
        $post_data['cus_add1'] = $request->address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $request->customer_mobile;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";

        #Before  going to initiate the payment order status need to insert or update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency'],
                'application_id' => $post_data['application_id']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');


        return response()->json([
            'success' => true,
            'data' => $payment_options
        ]);
        // if (!is_array($payment_options)) {
        //     print_r($payment_options);
        //     $payment_options = array();
        // }
    }

    public function payViaAjax(Request $request)
    {

        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = $request->amount; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique
        $post_data['application_id'] = $request->application_id;

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $request->customer_name;
        $post_data['cus_email'] = $request->customer_email;
        $post_data['cus_add1'] = $request->address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $request->customer_mobile;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";


        #Before  going to initiate the payment order status need to update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency'],
                'application_id' => $post_data['application_id']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function success(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();
        $order_detials = DB::table('orders')->where('transaction_id', $tran_id)->select('transaction_id', 'name', 'status', 'email', 'phone', 'currency', 'amount', 'application_id')->first();
        //
        if ($order_detials->status == 'Pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
            if ($validation) {
                return redirect()->away('https://visasaimon.com/online-payment?success=true');
            }
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            return redirect()->away('https://visasaimon.com/online-payment?success=true');
        } else {
            return redirect()->away('https://visasaimon.com/online-payment?success=false');
        }
    }

    public function successOld(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
        $sslc = new SslCommerzNotification();
        $order_detials = DB::table('orders')->where('transaction_id', $tran_id)->select('transaction_id', 'name', 'status', 'email', 'phone', 'currency', 'amount', 'application_id')->first();
        $account_id = 6;
        $employee_id = 1;
        $bank_id = 1;
        $date = date('Y-m-d');
        $time = date('h:i:s A');
        //
        if ($order_detials->status == 'Pending') {
            $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);
            if ($validation) {
                $update_product = DB::table('orders')->where('transaction_id', $tran_id)->update(['status' => 'Processing']);
                $data = WalkinAppInfo::with('checklist')->find($order_detials->application_id);
                $data->update([
                    'payment_status' => 2,
                    'paid_amount' => $order_detials->amount
                ]);
                //Update Bank Balance
                $bank_data = Bank::where('account_id', $account_id)->where('employee_id', $employee_id)->first();
                if ($bank_data) {
                    $bank_data->balance += $order_detials->amount;
                    $bank_data->update();
                }
                // Payment Record Creation
                $note = 'Online Payment via SSLCommerz, Transaction ID: ' . $tran_id;
                $payment_record  = [
                    'walkin_app_info_id' => $data->id,
                    'payment_status'    => 2,
                    'paid_amount'       => $order_detials->amount,
                    'payment'           => $order_detials->amount,
                    'due_amount'        => 0,
                    'payment_note'      => $note,
                    'payment_date'      => $date,
                    'payment_time'      => $time,
                    'payment_by'        => $data->customer_id,
                    'visa_fee'          => $data->checklist->price - $data->checklist->service_charge,
                    'service_charge'    => $data->checklist->service_charge,
                    'account_type'      => $bank_id,
                    'account_id'        => $account_id,
                ];
                $payment    = Payment::create($payment_record);
                // for send Email -----------------------------------------------------------
                $data = [
                    'payment' => $order_detials->amount,
                    'datas' => $data,
                    'payment_status' => 2
                ];
                Mail::to($order_detials->email)->send(new TestEmail($data)); // wrong template
                // start mobile sms -----------------------------------------------------------
                $url = "https://bulksmsbd.net/api/smsapi";
                $textBody = "Thank You $order_detials->name . Your Visa Application Payment $order_detials->amount is received.";
                $data = [
                    "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                    "senderid" => "SaimonGroup",
                    "number"    => $order_detials->phone,
                    "message"  => $textBody
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                // end mobile sms -----------------------------------------------------------
                return redirect()->away('https://visasaimon.com/online-payment?success=true');

                // alvis link
            }
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            $data = WalkinAppInfo::find($order_detials->application_id);
            $data->update([
                'payment_status' => 2,
                'paid_amount' => $order_detials->amount
            ]);
            //Update Bank Balance
            $bank_data = Bank::where('account_id', $account_id)->where('employee_id', $employee_id)->first();
            if ($bank_data) {
                $bank_data->balance += $order_detials->amount;
                $bank_data->update();
            }
            // Payment Record Creation
            $note = 'Online Payment via SSLCommerz, Transaction ID: ' . $tran_id;
            $payment_record  = [
                'walkin_app_info_id' => $data->id,
                'payment_status'    => 2,
                'paid_amount'       => $order_detials->amount,
                'payment'           => $order_detials->amount,
                'due_amount'        => 0,
                'payment_note'      => $note,
                'payment_date'      => $date,
                'payment_time'      => $time,
                'payment_by'        => $data->customer_id,
                'visa_fee'          => $data->checklist->price - $data->checklist->service_charge,
                'service_charge'    => $data->checklist->service_charge,
                'account_type'      => $bank_id,
                'account_id'        => $account_id,
            ];
            $payment    = Payment::create($payment_record);
            // for send Email -----------------------------------------------------------
            $data = [
                'payment' => $order_detials->amount,
                'datas' => $data,
                'payment_status' => 2
            ];
            Mail::to($order_detials->email)->send(new TestEmail($data));
            // start mobile sms -----------------------------------------------------------
            $url = "https://bulksmsbd.net/api/smsapi";
            $textBody = "Thank You $order_detials->name . Your Visa Application Payment $order_detials->amount is received.";
            $data = [
                "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                "senderid" => "SaimonGroup",
                "number"    => $order_detials->phone,
                "message"          => $textBody
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            // end mobile sms -----------------------------------------------------------

            return redirect()->away('https://visasaimon.com/online-payment?success=true');
            // alvis link
        } else {
            return redirect()->away('https://visasaimon.com/online-payment?success=false');
        }
    }
    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
            //            echo "Transaction is Falied";
            return redirect()->away('https://visasaimon.com/online-payment?success=false');
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            //            echo "Transaction is already Successful";
            return redirect()->away('https://visasaimon.com/online-payment?success=true');
        } else {
            //            echo "Transaction is Invalid";
            return redirect()->away('https://visasaimon.com/online-payment?success=false');
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
            //            echo "Transaction is Cancel";
            return redirect('https://visasaimon.com/profile');
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            //            echo "Transaction is Invalid";
            return view('error');
        }
    }

    public function ipnOld(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order_details->amount, $order_details->currency);
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

    public function ipn(Request $request)
    {
        if (!$request->input('tran_id')) {
            return response()->json(['message' => 'Invalid Data'], 400);
        }

        $tran_id = $request->input('tran_id');
        // Fetch order details
        $order = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $sslc = new SslCommerzNotification();
        $account_id = 6;
        $employee_id = 1;
        $bank_id = 1;
        $date = date('Y-m-d');
        $time = date('h:i:s A');

        try {
            if ($order->status === 'Pending') {

                // Validate transaction
                $validation = $sslc->orderValidate($request->all(), $tran_id, $order->amount, $order->currency);

                if ($validation === TRUE) {
                    DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    // Update WalkInAppInfo and related data
                    $app = WalkinAppInfo::with('checklist')->find($order->application_id);

                    if ($app) {
                        $app->update([
                            'payment_status' => 2,
                            'paid_amount' => $order->amount
                        ]);
                    }

                    // Update bank balance
                    $bank = Bank::where('account_id', $account_id)
                        ->where('employee_id', $employee_id)
                        ->first();

                    if ($bank) {
                        $bank->balance += $order->amount;
                        $bank->update();
                    }

                    // Prevent duplicate payment records
                    $existingPayment = Payment::where('payment_note', 'like', "%$tran_id%")->first();

                    if (!$existingPayment && $app) {
                        $payment_record = [
                            'walkin_app_info_id' => $app->id,
                            'payment_status' => 2,
                            'paid_amount' => $order->amount,
                            'payment' => $order->amount,
                            'due_amount' => 0,
                            'payment_note' => 'Online Payment via SSLCommerz, Transaction ID: ' . $tran_id,
                            'payment_date' => $date,
                            'payment_time' => $time,
                            'payment_by' => $app->customer_id,
                            'visa_fee' => $app->checklist->price - $app->checklist->service_charge,
                            'service_charge' => $app->checklist->service_charge,
                            'account_type' => $bank_id,
                            'account_id' => $account_id,
                        ];
                        Payment::create($payment_record);
                    }

                    // Send confirmation email
                    if (!empty($order->email)) {
                        $emailData = [
                            'payment' => $order->amount,
                            'datas' => $app,
                            'payment_status' => 2
                        ];
                        Mail::to($order->email)->send(new TestEmail($emailData));
                    }

                    // Send SMS
                    if (!empty($order->phone)) {
                        $url = "https://bulksmsbd.net/api/smsapi";
                        $textBody = "Thank You {$order->name}. Your Visa Application Payment {$order->amount} is received.";
                        $smsData = [
                            "api_key" => "SNKgKk4mULTv0V7Hy9GE",
                            "senderid" => "SaimonGroup",
                            "number" => $order->phone,
                            "message" => $textBody
                        ];
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $smsData);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_exec($ch);
                        curl_close($ch);
                    }

                    return response()->json(['message' => 'IPN processed successfully'], 200);
                } else {
                    return response()->json(['message' => 'Transaction validation failed'], 400);
                }
            } elseif ($order->status === 'Processing' || $order->status === 'Complete') {
                // Already processed, do nothing
                return response()->json(['message' => 'Transaction already completed'], 200);
            } else {
                // Invalid transaction
                return response()->json(['message' => 'Invalid Transaction'], 400);
            }
        } catch (Exception $e) {
            \Log::error('SSLCommerz IPN Error: ' . $e->getMessage(), [
                'tran_id' => $tran_id,
                'data' => $request->all()
            ]);
            return response()->json(['message' => 'Server Error'], 500);
        }
    }


    public function testSslCommerz()
    {
        $post_data = [
            'store_id' => 'b2gso6901b1b03afb3',
            'store_passwd' => 'b2gso6901b1b03afb3@ssl',
            'total_amount' => '100',
            'currency' => 'EUR',
            'tran_id' => 'REF123',
            'success_url' => 'http://yoursite.com/success.php',
            'fail_url' => 'http://yoursite.com/fail.php',
            'cancel_url' => 'http://yoursite.com/cancel.php',

            'cus_name' => 'Customer Name',
            'cus_email' => 'cust@yahoo.com',
            'cus_add1' => 'Dhaka',
            'cus_add2' => 'Dhaka',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => '01711111111',
            'cus_fax' => '01711111111',

            'ship_name' => 'Customer Name',
            'ship_add1' => 'Dhaka',
            'ship_add2' => 'Dhaka',
            'ship_city' => 'Dhaka',
            'ship_state' => 'Dhaka',
            'ship_postcode' => '1000',
            'ship_country' => 'Bangladesh',

            'multi_card_name' => 'mastercard,visacard,amexcard',
            'value_a' => 'ref001_A',
            'value_b' => 'ref002_B',
            'value_c' => 'ref003_C',
            'value_d' => 'ref004_D',
            'shipping_method' => 'NO',
            'product_name' => 'Computer',
            'product_category' => 'Goods',
            'product_profile' => 'physical-goods',
        ];

        // Send request to SSLCommerz
        $response = Http::asForm()->post('https://sandbox.sslcommerz.com/gwprocess/v4/api.php', $post_data);

        $result = $response->json();

        // Handle response
        if (isset($result['status']) && $result['status'] == 'SUCCESS') {
            $gatewayUrl = $result['GatewayPageURL'];
            return redirect()->away($gatewayUrl); // Redirect to payment page
        } else {
            // Debug if something went wrong
            return response()->json([
                'error' => 'SSLCommerz initiation failed',
                'details' => $result
            ]);
        }
    }
}
