@extends('auth.layout.app')

@section('title', 'Register - Step 3')

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
                <div class="w-7 h-7 flex items-center justify-center rounded-full bg-indigo-600 text-white font-bold text-xs shadow-md">3</div>
                <span class="text-xs font-medium text-indigo-600 mt-2 text-center">Identification<br><span class="text-gray-400">Verify identity</span></span>
            </div>
        </div>
    </div>

    <p class="p-4 bg-green-50 text-sm text-gray-700 rounded-lg mb-6 border border-green-200">
        <strong class="text-green-600">To ensure a safe trading environment,</strong> we require all users to verify their identity. Your information is encrypted and secure.
    </p>

    <form method="POST" action="{{ route('register.complete') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-4">
            <label class="block text-sm font-medium text-gray-700">ID Type</label>
            <select name="id_type" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
                <option value="driver_license">Driver's License</option>
                <option value="national_id">National ID Card</option>
                <option value="passport">Passport</option>
            </select>
            
            <label class="block text-sm font-medium text-gray-700">ID Number</label>
            <input type="text" name="id_number" placeholder="Enter your ID Number" required
                   class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">
            
            <label class="block text-sm font-medium text-gray-700">Upload ID Document</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-indigo-500 transition duration-150">
                <input type="file" name="id_document" id="id_document" class="hidden" accept="image/png, image/jpeg, application/pdf">
                <label for="id_document" class="cursor-pointer block">
                    <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 014 4v16a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h3"></path>
                    </svg>
                    <p class="mt-2 text-sm font-medium text-gray-600">Click to upload or drag and drop</p>
                    <p class="text-xs text-gray-500">PNG, JPG or PDF (Max. 10MB)</p>
                </label>
                <p id="file-name" class="mt-2 text-xs text-green-600 font-semibold"></p>
            </div>
            
            <p class="text-xs text-gray-500 pt-2">
                Please ensure your ID is clear and all details are visible.
            </p>

            <div class="pt-4">
                <label class="flex items-start text-xs text-gray-600">
                    <input type="checkbox" name="agree_terms" required class="mt-1 mr-2 rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                    By submitting, you agree to PocketRader's
                    <a href="#" class="text-indigo-600 hover:underline">Terms of Service</a> and
                    <a href="#" class="text-indigo-600 hover:underline">Privacy Policy</a>.
                </label>
            </div>
        </div>

        <div class="flex space-x-4 mt-6">
            <a href="{{ url('/register/step2') }}"
               class="flex-1 text-center py-3 px-4 border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition duration-150 shadow-sm">
                Back
            </a>
            <button type="submit"
                    class="flex-1 bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50 transition duration-150 shadow-lg shadow-indigo-200">
                Complete Registration
            </button>
        </div>
        
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