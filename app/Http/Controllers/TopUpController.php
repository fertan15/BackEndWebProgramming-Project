<?php

namespace App\Http\Controllers;

use App\Models\Users;
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
        $serverKey = config('services.midtrans.server_key');

        if (!$serverKey) {
            return response()->json(['error' => 'Server Key tidak ditemukan di .env'], 500);
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
            // Menggunakan withoutVerifying() untuk menghindari error SSL di localhost
            $response = Http::withoutVerifying()
                ->withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            $result = $response->json();

            if ($response->successful() && isset($result['token'])) {
                return response()->json(['snap_token' => $result['token']]);
            }

            return response()->json(['error' => $result['error_messages'][0] ?? 'Midtrans Error'], 500);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Koneksi gagal: ' . $e->getMessage()], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        Log::info("Webhook Masuk: " . $request->order_id);

        if ($hashed === $request->signature_key) {
            if (in_array($request->transaction_status, ['capture', 'settlement'])) {
                $parts = explode('-', $request->order_id);
                $userId = $parts[1] ?? null;

                // Mencari menggunakan model Users sesuai struktur Anda
                $user = Users::find($userId);

                if ($user) {
                    $idrAmount = (float)$request->gross_amount;
                    $usdAmount = $idrAmount / 15800;

                    // Update Balance
                    $user->balance += $usdAmount;
                    $user->save();

                    // Simpan Riwayat
                    WalletTransactions::create([
                        'user_id' => $user->id,
                        'reference_order_id' => $request->order_id,
                        'amount' => $usdAmount,
                        'transaction_type' => 'TOPUP',
                        'description' => 'Topup Midtrans Rp ' . number_format($idrAmount, 0, ',', '.'),
                        'created_at' => now(), // Karena timestamps dinonaktifkan di model
                    ]);

                    Log::info("Update Berhasil: User ID {$userId} saldo bertambah {$usdAmount}");
                }
            }
        }
        return response()->json(['status' => 'OK']);
    }
}