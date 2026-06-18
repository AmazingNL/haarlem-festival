<?php
/** @var string $title */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Scan Ticket</h1>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <p class="text-muted mb-3">
                    Point the camera at a ticket QR code to validate entry.
                </p>
                <div id="qr-reader" style="width:100%;"></div>
            </div>
        </div>

        <div id="scan-result" class="d-none mb-3">
            <div id="result-card" class="alert mb-2" role="alert">
                <h5 id="result-title" class="alert-heading mb-1"></h5>
                <p id="result-message" class="mb-0 small"></p>
            </div>
            <div class="text-center">
                <button id="btn-scan-next" class="btn btn-primary">Scan next ticket</button>
            </div>
        </div>

    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    var resultBox    = document.getElementById('scan-result');
    var resultCard   = document.getElementById('result-card');
    var resultTitle  = document.getElementById('result-title');
    var resultMsg    = document.getElementById('result-message');
    var btnScanNext  = document.getElementById('btn-scan-next');

    var MESSAGES = {
        ok:              { cls: 'alert-success', title: 'Valid — entry granted',   msg: 'Ticket marked as used.' },
        already_scanned: { cls: 'alert-warning', title: 'Already scanned',         msg: 'This ticket was used before.' },
        cancelled:       { cls: 'alert-danger',  title: 'Ticket cancelled',        msg: 'This ticket is no longer valid.' },
        not_found:       { cls: 'alert-danger',  title: 'Unknown QR code',         msg: 'No matching ticket found.' },
    };

    function showResult(key) {
        var info = MESSAGES[key] || MESSAGES['not_found'];
        resultCard.className     = 'alert ' + info.cls;
        resultTitle.textContent  = info.title;
        resultMsg.textContent    = info.msg;
        resultBox.classList.remove('d-none');
    }

    var activeScanner = null;
    var scanning      = false;

    function startScanner() {
        scanning = false;
        activeScanner = new Html5QrcodeScanner('qr-reader', { fps: 10, qrbox: 250 }, false);

        activeScanner.render(function (token) {
            if (scanning) return;
            scanning = true;

            if (!/^[a-f0-9]{64}$/.test(token)) {
                activeScanner.clear().catch(function () {});
                showResult('not_found');
                return;
            }

            fetch('/admin/tickets/' + token + '/scan', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    activeScanner.clear().catch(function () {});
                    showResult(data.result || 'not_found');
                })
                .catch(function () {
                    activeScanner.clear().catch(function () {});
                    showResult('not_found');
                });
        });
    }

    btnScanNext.addEventListener('click', function () {
        resultBox.classList.add('d-none');
        startScanner();
    });

    startScanner();
}());
</script>
