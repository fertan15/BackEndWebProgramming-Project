@extends('auth.layout.app')

@section('title', 'Register - Step 3')

@section('content')
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900">Verify your identity</h2>
        <p class="text-sm text-slate-600 mt-2">Complete your registration securely</p>
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
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-xs shadow-md">3</div>
                <span class="text-xs font-medium text-indigo-600 mt-2 text-center">Identification<br><span class="text-gray-400">Verify identity</span></span>
            </div>
        </div>
    </div>

    <div class="p-4 bg-green-50 border border-green-200 rounded-lg mb-6">
        <p class="text-sm text-slate-700">
            <span class="font-semibold text-green-600">🔒 Secure verification</span> - Your information is encrypted and secure. This helps us maintain a safe trading environment.
        </p>
    </div>

    <form method="POST" action="{{ route('register.complete') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-5">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">ID Type</label>
                <select name="id_type" required
                        class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                    <option value="driver_license">Driver's License</option>
                    <option value="national_id">National ID Card</option>
                    <option value="passport">Passport</option>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">ID Number</label>
                <input type="text" name="id_number" placeholder="Enter your ID Number" required
                       class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-slate-700">Upload ID Document</label>
                <div class="border-2 border-dashed border-slate-300 rounded-lg p-8 text-center cursor-pointer hover:border-indigo-500 hover:bg-indigo-50 transition duration-150">
                    <input type="file" name="id_document" id="id_document" class="hidden" accept="image/png, image/jpeg, application/pdf">
                    <label for="id_document" class="cursor-pointer block">
                        <svg class="mx-auto h-12 w-12 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 014 4v16a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h3"></path>
                        </svg>
                        <p class="mt-2 text-sm font-semibold text-slate-700">Click to upload or drag and drop</p>
                        <p class="text-xs text-slate-600">PNG, JPG or PDF (Max. 10MB)</p>
                    </label>
                    <p id="file-name" class="mt-2 text-xs text-green-600 font-semibold"></p>
                </div>
                <p class="text-xs text-slate-600">
                    Make sure your ID is clear and all details are visible.
                </p>
            </div>

            <div class="pt-2">
                <label class="flex items-start gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="agree_terms" required class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span>I agree to PocketRader's <a href="#" class="text-indigo-600 hover:underline font-semibold">Terms of Service</a> and <a href="#" class="text-indigo-600 hover:underline font-semibold">Privacy Policy</a></span>
                </label>
            </div>8">
            <a href="{{ url('/register/step2') }}"
               class="flex-1 text-center py-3 px-4 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition duration-150 shadow-sm">
                Back
            </a>
            <button type="submit"
                    class="flex-1 bg-[#365AF7] text-white font-semibold py-3 px-4 rounded-lg hover:bg-[#2f4ed6] focus:outline-none focus:ring-4 focus:ring-indigo-300 transition duration-150 shadow-md shadow-indigo-200">
                Complete Registration
            </button>
        </div>
        
        <button type="button" 
                class="w-full mt-4 text-center py-3 px-4 text-sm text-indigo-600 font-semibold hover:text-indigo-700 transition duration-150">
            Skip for now
        <button type="button" 
                class="w-full mt-4 text-center py-3 px-4 text-sm text-indigo-600 font-semibold hover:text-indigo-700 transition duration-150">
            Skip for now (verify later)
        </button>
    </form>

    <script>
        // Display selected file name
        document.getElementById('id_document').addEventListener('change', function() {
            const fileNameDisplay = document.getElementById('file-name');
            if (this.files.length > 0) {
                fileNameDisplay.textContent = 'Selected file: ' + this.files[0].name;
            } else {
                fileNameDisplay.textContent = '';
            }
        });
    </script>
@endsection