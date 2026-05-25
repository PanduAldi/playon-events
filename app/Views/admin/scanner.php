<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>QR Scanner Check-in - Playon Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>QR Scanner<?= $this->endSection() ?>

<?= $this->section('page_description') ?><p>Arahkan kamera ke QR Code peserta untuk melakukan check-in otomatis.</p><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .scanner-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-2xl);
        align-items: start;
    }

    .camera-box {
        background-color: var(--color-ink);
        border-radius: var(--radius-md);
        overflow: hidden;
        position: relative;
    }

    .camera-box #reader {
        width: 100% !important;
    }

    .camera-box #reader video {
        border-radius: var(--radius-md);
    }

    .result-card {
        padding: var(--space-xl);
        border-radius: var(--radius-md);
        text-align: center;
        animation: fadeSlideIn 0.4s ease;
    }

    @keyframes fadeSlideIn {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .result-success {
        background-color: #e8f5e9;
        border: 2px solid #66bb6a;
        color: #1b5e20;
    }

    .result-error {
        background-color: #ffebee;
        border: 2px solid #ef5350;
        color: #b71c1c;
    }

    .result-warning {
        background-color: #fff8e1;
        border: 2px solid #ffca28;
        color: #e65100;
    }

    .result-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--space-md);
    }

    .result-icon-success { background-color: #c8e6c9; }
    .result-icon-error   { background-color: #ffcdd2; }
    .result-icon-warning { background-color: #ffecb3; }

    .participant-detail {
        background-color: var(--color-canvas);
        border: 1px solid var(--color-mute);
        border-radius: var(--radius-md);
        padding: var(--space-lg);
        margin-top: var(--space-lg);
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: var(--space-sm) 0;
        border-bottom: 1px solid var(--color-canvas-soft);
        font-size: 15px;
    }

    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: var(--color-body-mid); }
    .detail-value { font-weight: 600; color: var(--color-ink); }

    .idle-state {
        text-align: center;
        padding: var(--space-4xl) var(--space-xl);
        color: var(--color-body-mid);
    }

    .idle-state svg { margin-bottom: var(--space-lg); opacity: 0.4; }

    .scan-history {
        margin-top: var(--space-2xl);
    }

    .history-item {
        display: flex;
        align-items: center;
        gap: var(--space-md);
        padding: var(--space-sm) var(--space-md);
        background-color: var(--color-canvas);
        border: 1px solid var(--color-mute);
        border-radius: var(--radius-sm);
        margin-bottom: var(--space-xs);
        font-size: 14px;
        animation: fadeSlideIn 0.3s ease;
    }

    .history-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .dot-green  { background-color: #66bb6a; }
    .dot-red    { background-color: #ef5350; }
    .dot-yellow { background-color: #ffca28; }

    @media (max-width: 900px) {
        .scanner-layout { grid-template-columns: 1fr; }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="scanner-layout">
        <div>
            <div class="camera-box">
                <div id="reader"></div>
            </div>
            <p style="text-align: center; font-size: 13px; color: var(--color-body-mid); margin-top: var(--space-sm);">
                Pastikan kamera mengizinkan akses. Scan akan berjalan otomatis.
            </p>
        </div>

        <div>
            <div id="resultArea">
                <div class="idle-state">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                    </svg>
                    <h3 style="margin-bottom: var(--space-sm);">Menunggu Pemindaian</h3>
                    <p>Hasil scan akan muncul di sini secara otomatis saat QR Code peserta terdeteksi oleh kamera.</p>
                </div>
            </div>

            <div class="scan-history">
                <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: var(--color-body-mid); margin-bottom: var(--space-sm);">Riwayat Scan</h4>
                <div id="historyList">
                    <p style="font-size: 14px; color: var(--color-mute);">Belum ada riwayat.</p>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    const BASE_URL = '<?= base_url() ?>';
    const CSRF_NAME = '<?= csrf_token() ?>';
    let checkedInCount = 0;
    let isProcessing = false;
    let lastScannedToken = '';
    let lastScanTime = 0;

    const html5QrCode = new Html5Qrcode('reader');

    const onScanSuccess = (decodedText) => {
        const now = Date.now();
        if (isProcessing || decodedText === lastScannedToken && now - lastScanTime < 3000) {
            return;
        }

        lastScannedToken = decodedText;
        lastScanTime = now;
        isProcessing = true;

        fetch(`${BASE_URL}/admin/scanner/checkin`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_NAME,
            },
            body: JSON.stringify({ token: decodedText }),
        })
        .then(response => response.json())
        .then(showScanResult)
        .catch(showScanError)
        .finally(() => {
            setTimeout(() => isProcessing = false, 1000);
        });
    };

    const onScanFailure = () => {
        // Ignore scan failures, only act on successful scans.
    };

    const showScanResult = (data) => {
        const resultArea = document.getElementById('resultArea');
        const historyList = document.getElementById('historyList');
        const checkedCount = document.getElementById('checkedCount');

        let resultClass = 'result-warning';
        let iconClass = 'result-icon-warning';
        let title = 'Hasil Scan';
        let message = data.message || 'Tidak ada data.';

        if (data.success) {
            resultClass = 'result-success';
            iconClass = 'result-icon-success';
            title = 'Check-in Berhasil';
            checkedInCount += 1;
            checkedCount.textContent = checkedInCount;
        } else {
            resultClass = data.errorType === 'invalid' ? 'result-error' : 'result-warning';
            iconClass = data.errorType === 'invalid' ? 'result-icon-error' : 'result-icon-warning';
        }

        resultArea.innerHTML = `
            <div class="result-card ${resultClass}">
                <div class="result-icon ${iconClass}">${data.success ? '✓' : '!'}</div>
                <h3 style="margin-bottom: var(--space-sm);">${title}</h3>
                <p style="margin-bottom: var(--space-sm);">${message}</p>
                ${data.participant ? `
                    <div class="participant-detail">
                        <div class="detail-row"><span class="detail-label">Nama</span><span class="detail-value">${data.participant.full_name}</span></div>
                        <div class="detail-row"><span class="detail-label">BIB</span><span class="detail-value">${data.participant.bib_number}</span></div>
                        <div class="detail-row"><span class="detail-label">Event</span><span class="detail-value">${data.participant.event_name}</span></div>
                        <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value">${data.participant.status}</span></div>
                    </div>` : ''}
            </div>`;

        const historyRow = document.createElement('div');
        historyRow.className = 'history-item';
        historyRow.innerHTML = `
            <span class="history-dot ${data.success ? 'dot-green' : (data.errorType === 'invalid' ? 'dot-red' : 'dot-yellow')}"></span>
            <div style="flex: 1;">
                <div style="font-weight: 700;">${data.participant ? data.participant.full_name : 'Scan gagal'}</div>
                <div style="font-size: 13px; color: var(--color-body-mid);">${message}</div>
            </div>
            <div style="font-size: 13px; color: var(--color-body-mid);">${new Date().toLocaleTimeString()}</div>
        `;

        if (historyList.firstChild && historyList.firstChild.tagName === 'P') {
            historyList.innerHTML = '';
        }

        historyList.prepend(historyRow);
        setTimeout(() => {
            if (historyList.children.length > 8) {
                historyList.removeChild(historyList.lastChild);
            }
        }, 500);
    };

    const showScanError = (error) => {
        const resultArea = document.getElementById('resultArea');
        resultArea.innerHTML = `
            <div class="result-card result-error">
                <div class="result-icon result-icon-error">!</div>
                <h3 style="margin-bottom: var(--space-sm);">Kamera Tidak Tersedia</h3>
                <p style="margin-bottom: var(--space-sm);">${error}</p>
            </div>`;
    };

    html5QrCode.start(
        { facingMode: 'environment' },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        onScanSuccess,
        onScanFailure
    ).catch(showScanError);
</script>
<?= $this->endSection() ?>
