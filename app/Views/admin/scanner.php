<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Scanner Check-in - Playon Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        .app-shell { display: flex; min-height: 100vh; }
        .sidebar {
            width: 260px;
            background-color: var(--color-ink);
            color: var(--color-canvas-soft);
            padding: var(--space-2xl) var(--space-xl);
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            font-family: var(--font-display);
            font-size: 24px;
            color: var(--color-primary);
            margin-bottom: var(--space-3xl);
            font-weight: 700;
        }
        .nav-item {
            display: block;
            padding: var(--space-sm) var(--space-md);
            color: var(--color-mute);
            border-radius: var(--radius-sm);
            margin-bottom: var(--space-xs);
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-item:hover, .nav-item.active {
            background-color: var(--color-ink-soft);
            color: var(--color-on-primary);
        }
        .nav-item.active { border-left: 3px solid var(--color-primary); }
        .main-content {
            flex: 1;
            background-color: var(--color-canvas-soft);
            padding: var(--space-3xl) var(--space-2xl);
            overflow-y: auto;
        }

        /* Scanner specific */
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
        
        /* Result cards */
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
            .sidebar { display: none; }
            .scanner-layout { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <div class="sidebar">
            <div class="sidebar-brand">Playon Admin</div>
            <nav style="flex-grow: 1;">
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-item">Dashboard</a>
                <a href="<?= base_url('admin/events') ?>" class="nav-item">Events</a>
                <a href="<?= base_url('admin/participants') ?>" class="nav-item">Peserta</a>
                <a href="<?= base_url('admin/scanner') ?>" class="nav-item active">Scanner</a>
            </nav>
            <div style="margin-top: auto;">
                <a href="<?= base_url('logout') ?>" class="nav-item" style="color: #ef9a9a;">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--space-2xl);">
                <div>
                    <h1 style="font-size: 32px; margin-bottom: var(--space-xs);">QR Scanner</h1>
                    <p style="color: var(--color-body-mid);">Arahkan kamera ke QR Code peserta untuk melakukan check-in otomatis.</p>
                </div>
                <div id="scanCount" style="font-family: var(--font-display); font-size: 14px; color: var(--color-body-mid);">
                    Sudah check-in: <strong id="checkedCount">0</strong>
                </div>
            </div>

            <div class="scanner-layout">
                <!-- Kamera -->
                <div>
                    <div class="camera-box">
                        <div id="reader"></div>
                    </div>
                    <p style="text-align: center; font-size: 13px; color: var(--color-body-mid); margin-top: var(--space-sm);">
                        Pastikan kamera mengizinkan akses. Scan akan berjalan otomatis.
                    </p>
                </div>

                <!-- Hasil Scan -->
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

                    <!-- Riwayat Scan -->
                    <div class="scan-history">
                        <h4 style="font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: var(--color-body-mid); margin-bottom: var(--space-sm);">Riwayat Scan</h4>
                        <div id="historyList">
                            <p style="font-size: 14px; color: var(--color-mute);">Belum ada riwayat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- html5-qrcode library from CDN -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        const BASE_URL = '<?= base_url() ?>';
        const CSRF_NAME = '<?= csrf_token() ?>';
        let checkedInCount = 0;
        let isProcessing = false;
        let lastScannedToken = '';
        let lastScanTime = 0;

        // Initialize scanner
        const html5QrCode = new Html5Qrcode("reader");
        
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: { width: 250, height: 250 } },
            onScanSuccess,
            onScanFailure
        ).catch(err => {
            document.getElementById('reader').innerHTML = `
                <div style="padding: 48px; text-align: center; color: #ef9a9a;">
                    <p style="font-size: 18px; font-weight: 600;">Gagal Mengakses Kamera</p>
                    <p style="font-size: 14px; margin-top: 8px;">${err}</p>
                    <p style="font-size: 13px; margin-top: 16px;">Pastikan Anda mengizinkan akses kamera di pengaturan peramban.</p>
                </div>
            `;
        });

        function onScanSuccess(decodedText) {
            const now = Date.now();
            // Debounce: prevent duplicate scans of the same token within 4 seconds
            if (isProcessing || (decodedText === lastScannedToken && (now - lastScanTime) < 4000)) {
                return;
            }

            isProcessing = true;
            lastScannedToken = decodedText;
            lastScanTime = now;

            // Show loading state
            document.getElementById('resultArea').innerHTML = `
                <div class="result-card" style="background-color: var(--color-canvas); border: 2px solid var(--color-mute);">
                    <div style="font-size: 18px; color: var(--color-ink);">⏳ Memproses...</div>
                    <p style="color: var(--color-body-mid); margin-top: 8px;">Memvalidasi tiket peserta...</p>
                </div>
            `;

            fetch(BASE_URL + '/admin/scanner/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ qr_token: decodedText })
            })
            .then(res => res.json())
            .then(data => {
                renderResult(data);
                addToHistory(data);
                if (data.success) {
                    checkedInCount++;
                    document.getElementById('checkedCount').textContent = checkedInCount;
                }
                // Allow next scan after 2 seconds
                setTimeout(() => { isProcessing = false; }, 2000);
            })
            .catch(err => {
                renderResult({
                    success: false,
                    type: 'error',
                    message: 'Terjadi kesalahan jaringan. Silakan coba lagi.'
                });
                setTimeout(() => { isProcessing = false; }, 2000);
            });
        }

        function onScanFailure(error) {
            // Silently ignore scan failures (no QR in frame)
        }

        function renderResult(data) {
            let cardClass = 'result-error';
            let iconClass = 'result-icon-error';
            let iconSvg = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';

            if (data.success) {
                cardClass = 'result-success';
                iconClass = 'result-icon-success';
                iconSvg = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>';
            } else if (data.type === 'unpaid' || data.type === 'already_checked_in') {
                cardClass = 'result-warning';
                iconClass = 'result-icon-warning';
                iconSvg = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>';
            }

            let detailHtml = '';
            if (data.data) {
                detailHtml = `
                    <div class="participant-detail">
                        <div class="detail-row">
                            <span class="detail-label">Nama</span>
                            <span class="detail-value">${data.data.full_name}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">BIB</span>
                            <span class="detail-value">${data.data.bib_number}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Event</span>
                            <span class="detail-value">${data.data.event_name}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Kategori</span>
                            <span class="detail-value">${data.data.category_name}</span>
                        </div>
                    </div>
                `;
            }

            document.getElementById('resultArea').innerHTML = `
                <div class="result-card ${cardClass}">
                    <div class="result-icon ${iconClass}">${iconSvg}</div>
                    <h3 style="font-size: 20px; margin-bottom: 4px;">${data.success ? 'Check-in Berhasil!' : 'Gagal'}</h3>
                    <p style="font-size: 15px;">${data.message}</p>
                    ${detailHtml}
                </div>
            `;
        }

        function addToHistory(data) {
            const historyList = document.getElementById('historyList');
            // Remove placeholder text if exists
            if (historyList.querySelector('p')) {
                historyList.innerHTML = '';
            }
            
            let dotClass = 'dot-red';
            if (data.success) dotClass = 'dot-green';
            else if (data.type === 'unpaid' || data.type === 'already_checked_in') dotClass = 'dot-yellow';

            const name = data.data ? data.data.full_name : 'Unknown';
            const bib = data.data ? data.data.bib_number : '-';
            const time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            const item = document.createElement('div');
            item.className = 'history-item';
            item.innerHTML = `
                <div class="history-dot ${dotClass}"></div>
                <div style="flex: 1;">
                    <strong>${name}</strong> <span style="color: var(--color-body-mid);">(${bib})</span>
                </div>
                <div style="color: var(--color-body-mid); font-size: 12px;">${time}</div>
            `;
            
            historyList.prepend(item);
        }
    </script>
</body>
</html>
