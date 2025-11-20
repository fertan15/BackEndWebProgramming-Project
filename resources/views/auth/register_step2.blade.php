@extends('auth.layout.app')

@section('title', 'Register - Step 2')

@section('content')
    <h2 class="text-xl font-semibold text-gray-800 mb-2">Create your account</h2>
    <p class="text-sm text-gray-500 mb-6">Join PocketRader and start trading today</p>

    <!-- Step Progress Indicator -->
    <div class="mb-8">
        <div class="flex justify-between items-center relative">
            <!-- Line -->
            <div class="absolute w-full top-3 h-1 bg-indigo-600 z-0"></div>
            <!-- Steps -->
            <div class="flex flex-col items-center z-10">
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-xs shadow-md">1</div>
                <span class="text-xs font-medium text-indigo-600 mt-2 text-center">Information<br><span class="text-gray-400">Basic Details</span></span>
            </div>
            <div class="flex flex-col items-center z-10">
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-xs shadow-md">2</div>
                <span class="text-xs font-medium text-indigo-600 mt-2 text-center">Verification<br><span class="text-gray-400">OTP code</span></span>
            </div>
            <div class="flex flex-col items-center z-10">
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-white border-2 border-indigo-200 text-indigo-600 font-bold text-xs shadow-sm">3</div>
                <span class="text-xs font-medium text-gray-500 mt-2 text-center">Identification<br><span class="text-gray-400">Verify identity</span></span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('register.storeStep2') }}">
        @csrf

        <div class="p-4 bg-indigo-50 border border-indigo-200 rounded-lg mb-6">
            <p class="text-sm text-gray-700">
                We've sent a 6-digit verification code to your email and phone number:
                <span class="font-semibold text-indigo-600">{{ session('register.email', 'EMAIL_ADDRESS') }}</span> /
                <span class="font-semibold text-indigo-600">{{ session('register.phone', 'PHONE_NUMBER') }}</span>
            </p>
        </div>

        <label class="block text-sm font-medium text-gray-700 mb-2" for="otp-input">Enter OTP Code</label>
        <div class="flex justify-between space-x-2 mb-6">
            <!-- OTP Input fields (using hidden inputs for actual submission) -->
            @for ($i = 0; $i < 6; $i++)
                <input type="text" maxlength="1" id="otp-{{ $i }}"
                       class="w-1/6 h-14 text-3xl text-center border-2 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                       inputmode="numeric" pattern="[0-9]" required>
            @endfor
            <!-- Hidden input for combined OTP value (will be populated by JS) -->
            <input type="hidden" name="otp_code" id="otp-code-hidden">
        </div>

        <p class="text-sm text-center text-gray-500 mb-6">
            Resend code in <span id="resend-timer" class="font-semibold text-indigo-600">56s</span>
        </p>

        <div class="flex space-x-4">
            <a href="{{ url('/register/step1') }}"
               class="flex-1 text-center py-3 px-4 border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition duration-150 shadow-sm">
                Back
            </a>
            <button type="submit"
                    class="flex-1 bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150 shadow-lg shadow-indigo-200">
                Verify
            </button>
        </div>
    </form>

    <script>
        // Simple client-side script for OTP field movement (UX improvement)
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.flex > input[type="text"]');
            
            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    // Move to next input on single character entry
                    if (input.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    // Combine all OTP values into the hidden field on change
                    document.getElementById('otp-code-hidden').value = 
                        Array.from(inputs).map(i => i.value).join('');
                });

                input.addEventListener('keydown', (e) => {
                    // Move to previous input on backspace if current is empty
                    if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });

            // Timer for the resend function (front-end only)
            let time = 56;
            const timerElement = document.getElementById('resend-timer');
            const interval = setInterval(() => {
                time--;
                if (time >= 0) {
                    timerElement.textContent = `${time}s`;
                } else {
                    clearInterval(interval);
                    timerElement.textContent = '0s';
                    // Here you would typically enable the resend button
                }
            }, 1000);
        });
    </script>
@endsection