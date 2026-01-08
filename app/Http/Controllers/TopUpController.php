<?php
namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;

class TopUpController extends Controller
{
    public function __construct()
    {
        // Set keys from your .env
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // CRITICAL FIX FOR ERROR 10023: Bypass SSL check on localhost
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ];
    }

    public function show()
    {
        return view('topup', ['currentUser' => Auth::user()]);
    }

    public function getSnapToken(Request $request)
    {
        // Re-apply SSL bypass right before the request for extra safety
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
        ];

        $user = Auth::user();
        $amount = (int)$request->amount_idr;

        $params = [
            'transaction_details' => [
                'order_id' => 'TOPUP-' . $user->id . '-' . time(),
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            // This will show you the REAL error if it fails
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } 

    public function handleNotification(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if (in_array($request->transaction_status, ['capture', 'settlement'])) {
                $userId = explode('-', $request->order_id)[1];
                $user = Users::find($userId);

                // Conversion: IDR to USD
                $usdAmount = (float)$request->gross_amount / 15800;
                $user->balance = (float)$user->balance + $usdAmount;
                $user->save();
            }
        }
    }
}