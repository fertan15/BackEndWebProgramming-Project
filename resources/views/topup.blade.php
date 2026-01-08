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

    // Fungsi untuk memformat angka menjadi ribuan (1.000.000)
    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        return rupiah;
    }

    // Live update format ribuan dan estimasi USD
    amountInput.onkeyup = function() {
        // Simpan angka murni (tanpa titik)
        let rawValue = this.value.replace(/\./g, '');
        
        // Update tampilan input dengan format titik
        this.value = formatRupiah(this.value);
        
        // Update estimasi USD
        const val = parseFloat(rawValue) || 0;
        usdResult.innerText = '$' + (val / rate).toFixed(2);
    };

    document.getElementById('pay-button').onclick = function(e) {
        e.preventDefault();
        
        // Ambil nilai asli (hilangkan titik sebelum dikirim ke controller)
        const idrValue = amountInput.value.replace(/\./g, '');

        if (!idrValue || idrValue < 10000) {
            alert("Minimal top up adalah Rp 10.000");
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
            body: JSON.stringify({ amount_idr: idrValue }) // Mengirim angka murni
        })
        .then(async r => {
            const res = await r.json();
            if (!r.ok) throw new Error(res.error || 'Terjadi kesalahan server');
            return res;
        })
        .then(data => {
            if (data.snap_token) {
                window.snap.pay(data.snap_token, {
                    onSuccess: (result) => { window.location.href = "/home?success=1"; },
                    onPending: (result) => { window.location.href = "/home?pending=1"; },
                    onError: (result) => { alert("Pembayaran gagal!"); resetBtn(); },
                    onClose: () => { alert("Popup ditutup"); resetBtn(); }
                });
            }
        })
        .catch(err => {
            alert(err.message);
            resetBtn();
        });

        function resetBtn() {
            const btn = document.getElementById('pay-button');
            btn.disabled = false;
            btn.innerText = "Pay Now";
        }
    };
</script>
@endsection