@extends('layout.main')

@section('main_contents')
<div class="container py-5 d-flex justify-content-center">
    <div class="card border-0 shadow-lg p-5" style="width: 450px; border-radius: 20px;">
        <h3 class="fw-bold text-center mb-1">Top Up Wallet</h3>
        <p class="text-muted text-center small mb-4">Pay in IDR, Receive in USD</p>

        <div class="p-3 bg-light rounded-4 mb-4">
            <div class="d-flex justify-content-between small text-secondary">
                <span>Current Balance:</span>
                <span class="fw-bold text-dark">${{ number_format((float)$currentUser->balance, 2) }}</span>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Amount to Pay (IDR)</label>
            <div class="input-group input-group-lg">
                <span class="input-group-text bg-white border-end-0">Rp</span>
                <input type="number" id="idrAmount" class="form-control border-start-0" placeholder="100000" min="10000">
            </div>
            
            <div class="mt-3 p-3 border rounded-3 bg-white shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-muted">You will receive:</span>
                    <span id="usdResult" class="h4 fw-bold text-primary mb-0">$0.00</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between small">
                    <span class="text-muted">Exchange Rate:</span>
                    <span class="text-secondary">$1 = Rp 15.800</span>
                </div>
            </div>
        </div>

        <button id="pay-button" class="btn btn-primary btn-lg w-100 py-3 rounded-pill fw-bold shadow">
            Proceed to Payment
        </button>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    const idrInput = document.getElementById('idrAmount');
    const usdText = document.getElementById('usdResult');
    const rate = 15800;

    // LIVE CONVERSION LOGIC
    idrInput.oninput = function() {
        let idrValue = parseFloat(this.value) || 0;
        let usdValue = idrValue / rate;
        usdText.innerText = '$' + usdValue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    };

    document.getElementById('pay-button').onclick = function() {
        const amount = idrInput.value;
        if (!amount || amount < 10000) {
            alert("Minimum payment is Rp 10.000");
            return;
        }

        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';

        fetch("{{ route('topup.snap') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ amount_idr: amount })
        })
        .then(r => r.json())
        .then(data => {
            if (data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function() { window.location.href = "/home?success=1"; },
                    onClose: function() { location.reload(); }
                });
            } else {
                alert("Error: " + (data.error || "Token failed"));
                location.reload();
            }
        })
        .catch(err => {
            alert("Connection error. Check console.");
            console.error(err);
            this.disabled = false;
            this.innerText = "Proceed to Payment";
        });
    };
</script>
@endsection