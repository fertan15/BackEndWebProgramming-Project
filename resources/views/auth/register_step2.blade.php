@extends('auth.layout.app')

@section('title', 'Register - Step 2')

@section('content')

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-sm font-semibold text-red-700">{{ session('error') }}</p>
        </div>
    @endif
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900">Verify your email</h2>
        <p class="text-sm text-slate-600 mt-2">We've sent a 6-digit code to your email</p>
    </div>

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
            <p class="text-sm text-slate-700">
                Sent to:
                <span class="font-semibold text-indigo-600">{{ session('register.email', 'EMAIL_ADDRESS') }}</span> 
            </p>
        </div>

        <label class="block text-sm font-semibold text-slate-700 mb-4">Enter OTP Code</label>
        <div class="flex justify-between space-x-2 mb-6">
            <!-- OTP Input fields (using hidden inputs for actual submission) -->
            @for ($i = 0; $i < 6; $i++)
                <input type="text" maxlength="1" id="otp-{{ $i }}"
                       class="w-1/6 h-14 text-3xl text-center border-2 border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 hover:border-slate-400"
                       inputmode="numeric" pattern="[0-9]" required>
            @endfor
            <!-- Hidden input for combined OTP value (will be populated by JS) -->
            <input type="hidden" name="otp_code" id="otp-code-hidden">
        </div>
        <button type="button" id="btn-resend" onclick="startResendTimer()" class="w-full text-sm text-center text-indigo-600 font-semibold mb-6 hover:text-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed">Resend Code</button>
        <div class="flex space-x-4">
            <a href="{{ url('/register/step1') }}"
               class="flex-1 text-center py-3 px-4 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition duration-150 shadow-sm">
                Back
            </a>
            <button type="submit"
                    class="flex-1 bg-[#365AF7] text-white font-semibold py-3 px-4 rounded-lg hover:bg-[#2f4ed6] focus:outline-none focus:ring-4 focus:ring-indigo-300 transition duration-150 shadow-md shadow-indigo-200">
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

            // Check if there's a saved timer on page load
            checkAndResumeTimer();
        });

        function checkAndResumeTimer() {
            const endTime = localStorage.getItem('otp_timer_end');
            if (endTime) {
                const now = Date.now();
                const remaining = Math.floor((endTime - now) / 1000);
                
                if (remaining > 0) {
                    // Resume the timer
                    const button = document.getElementById('btn-resend');
                    button.disabled = true;
                    button.classList.add('text-gray-500');
                    button.classList.remove('text-indigo-600');
                    startTimer(button, remaining);
                } else {
                    // Timer expired, clear localStorage
                    localStorage.removeItem('otp_timer_end');
                }
            }
        }

        function startResendTimer() {
            const button = document.getElementById('btn-resend');
            
            // Debug: Check if CSRF token exists
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log('CSRF Token:', csrfToken);
            
            if (!csrfToken) {
                alert('CSRF token tidak ditemukan! Refresh halaman.');
                return;
            }
            
            // 1. DISABLE BUTTON
            button.disabled = true;
            button.classList.add('text-gray-500');
            button.classList.remove('text-indigo-600');

            // 2. SEND OTP VIA AJAX
            console.log('Sending OTP request to /send-otp...');
            $.ajax({
                url: '/send-otp',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                dataType: 'json',
                success: function(response) {
                    console.log("OTP Berhasil dikirim ulang:", response);
                    alert("Kode OTP baru telah dikirim!\nDebug OTP: " + response.otp_debug);
                    
                    // Save timer end time to localStorage (60 seconds from now)
                    const endTime = Date.now() + (60 * 1000);
                    localStorage.setItem('otp_timer_end', endTime);
                    
                    // Start the timer
                    startTimer(button, 60);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error Status:", status);
                    console.error("AJAX Error:", error);
                    console.error("XHR Status:", xhr.status);
                    console.error("Response Text:", xhr.responseText);
                    
                    let errorMsg = "Gagal mengirim ulang kode.";
                    try {
                        let response = JSON.parse(xhr.responseText);
                        errorMsg = response.message || errorMsg;
                        console.error("Parsed error message:", errorMsg);
                    } catch(e) {
                        console.error("Could not parse error response");
                        errorMsg += " Status: " + xhr.status;
                    }
                    alert(errorMsg + "\n\nCek console untuk detail.");
                    
                    // Reset button if error
                    button.disabled = false;
                    button.textContent = "Kirim Ulang Kode";
                    button.classList.remove('text-gray-500');
                    button.classList.add('text-indigo-600');
                }
            });
        }

        function startTimer(button, initialTime) {
            let time = initialTime;
            const interval = setInterval(() => {
                time--;
                if (time > 0) {
                    button.innerHTML = `Resend code in <span class="text-gray-500">${time}s</span>`;
                } else {
                    clearInterval(interval);
                    button.disabled = false;
                    button.textContent = "Kirim Ulang Kode";
                    button.classList.remove('text-gray-500');
                    button.classList.add('text-indigo-600');
                    // Clear localStorage when timer ends
                    localStorage.removeItem('otp_timer_end');
                }
            }, 1000);
        }
    </script>
@endsection