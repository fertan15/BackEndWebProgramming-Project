@extends('layout.main')

@section('main_contents')
<div class="container py-5">
    <div class="card mx-auto shadow-sm" style="max-width: 400px; border-radius: 15px;">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-3">Top Up Wallet</h4>
            <div class="mb-4 text-center p-3 bg-light rounded">
                <small class="text-muted d-block">Current Balance</small>
                <span class="h4 fw-bold text-primary">${{ number_format($currentUser->balance, 2) }}</span>
            </div>

            <label class="form-label small fw-bold">Amount to Pay (IDR)</label>
            <input type="text" id="idrAmountDisplay" class="form-control form-control-lg mb-3" placeholder="Contoh: 100.000">
            
            <div class="d-flex justify-content-between mb-4 px-1">
                <span class="small text-muted">Estimated USD:</span>
                <span id="usdResult" class="small fw-bold">$0.00</span>
            </div>

            <button id="pay-button" class="btn btn-primary w-100 py-2 fw-bold">Pay Now</button>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<script>
    const rate = 15800;
    const amountInput = document.getElementById('idrAmountDisplay');
    const usdResult = document.getElementById('usdResult');

    // Mencegah input selain angka
    amountInput.onkeypress = function(e) {
        if (!/[0-9]/.test(e.key)) e.preventDefault();
    };

    // Format Rupiah & Live USD
    amountInput.onkeyup = function() {
        let val = this.value.replace(/\./g, '');
        if (val !== "") {
            this.value = new Intl.NumberFormat('id-ID').format(val);
        }
        const rawValue = parseFloat(val) || 0;
        usdResult.innerText = '$' + (rawValue / rate).toFixed(2);
    };

    document.getElementById('pay-button').onclick = function(e) {
        e.preventDefault();
        const rawValue = amountInput.value.replace(/\./g, '');

        if (rawValue < 10000) {
            alert("Minimal Top Up Rp 10.000");
            return;
        }

        this.disabled = true;
        this.innerText = "Processing...";

        fetch("{{ route('topup.snap') }}", {
            method: "POST",
            headers: { 
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ amount_idr: rawValue })
        })
        .then(r => r.json())
        .then(data => {
            if (data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: (result) => { window.location.href = "/home?success=1"; },
                    onError: (result) => { alert("Gagal!"); location.reload(); },
                    onClose: () => { location.reload(); }
                });
            } else {
                alert(data.error);
                location.reload();
            }
        });
    };
</script>
@endsection