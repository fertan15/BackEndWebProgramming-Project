<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WalletTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TopUpController extends Controller
{
    public function show()
    {
        return view('topup', ['currentUser' => Auth::user()]);
    }

    public function getSnapToken(Request $request)
{
    $user = Auth::user();
    $amount = (int)$request->amount_idr;
    
    // Ambil Key dari config
    $serverKey = config('services.midtrans.server_key');

    // DEBUG: Cek apakah key terbaca (Hapus baris ini jika sudah ok)
    // dd($serverKey); 

    if (!$serverKey) {
        return response()->json(['error' => 'Server Key tidak ditemukan di config/services.php'], 500);
    }

    $params = [
        'transaction_details' => [
            'order_id' => 'TOPUP-' . $user->id . '-' . time(),
            'gross_amount' => $amount,
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email' => $user->email,
        ]
    ];

    try {
        $response = Http::withoutVerifying()
            ->withBasicAuth($serverKey, '') // Password dikosongkan
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

        $result = $response->json();

        // Jika unauthorized, response biasanya berisi pesan error spesifik
        if ($response->status() === 401) {
            return response()->json(['error' => 'Server Key Salah (Unauthorized). Periksa kembali Dashboard Midtrans Anda.'], 401);
        }

        if ($response->successful() && isset($result['token'])) {
            return response()->json(['snap_token' => $result['token']]);
        }

        return response()->json(['error' => $result['error_messages'][0] ?? 'Terjadi kesalahan'], 500);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function handleNotification(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed === $request->signature_key) {
            if (in_array($request->transaction_status, ['capture', 'settlement'])) {
                $parts = explode('-', $request->order_id);
                $userId = $parts[1] ?? null;

                $user = User::find($userId);
                if ($user) {
                    $idrAmount = (float)$request->gross_amount;
                    $usdAmount = $idrAmount / 15800;

                    $user->balance += $usdAmount;
                    $user->save();

                    WalletTransactions::create([
                        'user_id' => $user->id,
                        'reference_order_id' => $request->order_id,
                        'amount' => $usdAmount,
                        'transaction_type' => 'TOPUP',
                        'description' => 'Topup Midtrans Rp ' . number_format($idrAmount, 0, ',', '.'),
                    ]);
                }
            }
        }
        return response()->json(['status' => 'OK']);
    }
}