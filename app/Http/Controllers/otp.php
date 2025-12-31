<?php

namespace App\Http\Controllers;

use App\Mail\OtpEmail;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class Otp extends Controller
{
    public function sendOtp(Request $request)
    {
        // 1. Get user ID from session (during registration) or from Auth (after login)
        $userId = session('register.user_id') ?? Auth::id();

        if (!$userId) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan.'], 401);
        }

        $user = Users::find($userId);

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User tidak ditemukan di database.'], 404);
        }

        // 2. Generate OTP Baru (6 digit)
        $newOtp = (string) rand(100000, 999999);
        
        // 3. Set waktu kadaluarsa (misal: 5 menit dari sekarang)
        $expiresAt = Carbon::now()->addMinutes(5);

        $request->session()->put('otp', $newOtp);
        $request->session()->put('otp_expires_at', $expiresAt);
        // 4. Update data di tabel 'users' sesuai struktur DB kamu
        Users::where('id', $userId)->update([
            'otp_code' => $newOtp,
            'otp_expires_at' => $expiresAt
        ]);

        // 5. Kirim Email
        try {
            Mail::to($user->email)->send(new OtpEmail($newOtp));
            Log::info('OTP sent successfully to ' . $user->email . '. OTP: ' . $newOtp);
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('OTP Email sending failed: ' . $e->getMessage());
            // Return error response jika ada masalah
            return response()->json(['status' => 'error', 'message' => 'Gagal mengirim email: ' . $e->getMessage()], 500);
        }

        // 6. Return JSON sukses untuk ditangkap jQuery
        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP baru berhasil dikirim.',
            'otp_debug' => $newOtp  // For testing - remove in production
        ]);
    }
}
