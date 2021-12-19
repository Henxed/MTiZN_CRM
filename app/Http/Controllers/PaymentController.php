<?php

namespace App\Http\Controllers;

use App\Models\Boost;
use App\Models\Transaction;
use App\Models\User;
use App\Service\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);
    }

    public function create(Request $request, PaymentService $service)
    {
        $data = Boost::findOrFail($request->id);

        if($data && $data->status_id === 0){
            $amount = (float)$data->price;
            $description = 'Заказ #'. $data->orderID;


            if(!Auth::check()){
                $pass = \Str::random(10);
                $user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => explode("@", $request->email)[0],
                    'email' => $request->email,
                    'password' => Hash::make($pass),
                ]);
                $data->user_id = $user->id;
                $data->save();
                $new_account = 'We have created an account for you based on your email in order not to lose the order.<br>
                                <strong>Login:</strong> '.$user->email.'<br>
                                <strong>Password:</strong> '.$pass.'<br>
                                Your order is '. route('order', $data->orderID);
            }

            $transaction = Transaction::create([
                'amount' => $amount,
                'email' => Auth::check() ? Auth::user()->email : $user->email,
                'user_id' => Auth::check() ? Auth::user()->id : $user->id,
                'order_id' => $data->id,
                'description' => $description,
            ]);

            if($transaction){
                $link = $service->createPayment($amount, $description, [
                    'transaction_id' => $transaction->id,
                    'email' => $request->email,
                    'order_id' => $data->orderID
                ]);
            }

            $details = [
                'title' => 'You have placed an order',
                'body' => (Auth::check() ? 'You have placed an order for account boosting. Your order is '. route('order', $data->orderID) : $new_account) . ' <br> Pay for the order: '. $link
            ];

            \Mail::to($request->email)->send(new \App\Mail\NewOrder($details));

            return Inertia::location($link);
        }else{

            return Redirect::back()->with('error', 'You have already paid!');
        }

    }
    public function callback(Request $request, PaymentService $service)
    {
        $source = file_get_contents('php://input');
        $requestBody = json_decode($source, true);

        try {
            $notification = ($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED)
              ? new NotificationSucceeded($requestBody)
              : new NotificationWaitingForCapture($requestBody);
          } catch (Exception $e) {
              // Обработка ошибок при неверных данных
          }

        $payment = $notification->getObject();

        if(isset($payment->status) && $payment->status === 'waiting_for_capture'){

            if( (bool)$payment->paid === true){
                $metadata = (object)$payment->metadata;
                if(isset($metadata->transaction_id)){
                    $transactionId = (int)$metadata->transaction_id;

                    $transaction = Transaction::findOrFail($transactionId);
                    $transaction->status_id = 904; //wait
                    $transaction->save();

                    $boost = Boost::findOrFail($transaction->order_id);
                    $boost->status_id = 1; //wait
                    $boost->save();
                }
            }
        }

        if(isset($payment->status) && $payment->status === 'succeeded'){
            if( (bool)$payment->paid === true ){
                $metadata = (object)$payment->metadata;
                if(isset($metadata->transaction_id)){
                    $transactionId = (int)$metadata->transaction_id;

                    $transaction = Transaction::findOrFail($transactionId);
                    $transaction->status_id = 903; //success
                    $transaction->save();

                    $boost = Boost::findOrFail($transaction->order_id);
                    $boost->status_id = 5; //success
                    $boost->save();
                }
            }
        }

        if(isset($payment->status) && $payment->status === 'canceled'){
            if( (bool)$payment->paid === true ){
                $metadata = (object)$payment->metadata;
                if(isset($metadata->transaction_id)){
                    $transactionId = (int)$metadata->transaction_id;

                    $transaction = Transaction::findOrFail($transactionId);
                    $transaction->status_id = 902; //cancel
                    $transaction->save();

                    $boost = Boost::findOrFail($transaction->order_id);
                    $boost->status_id = 2; //cancel
                    $boost->save();
                }
            }
        }


    }
}
