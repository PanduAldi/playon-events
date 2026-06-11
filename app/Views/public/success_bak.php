<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Pendaftaran Berhasil - <?= esc($event['name']) ?><?= $this->endSection() ?>

<?= $this->section('nav_links') ?>
    <a href="<?= base_url('/') ?>" class="btn btn-text" style="font-weight: 600;">Beranda</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section style="padding: var(--space-4xl) 0; background-color: var(--color-canvas-soft); min-height: calc(100vh - 70px); display: flex; align-items: center; justify-content: center;">
        <div class="container" style="max-width: 600px; width: 100%;">
            
            <div class="card-content" style="background-color: var(--color-canvas); text-align: center; padding: var(--space-3xl) var(--space-xl);">
                <div style="width: 64px; height: 64px; background-color: #e8f5e9; color: #2e7d32; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                
                <h1 style="font-size: 32px; margin-bottom: var(--space-sm);">Pendaftaran Berhasil!</h1>
                <p style="color: var(--color-body); margin-bottom: var(--space-2xl);">
                    Terima kasih, <strong><?= esc($participant['full_name']) ?></strong>. Anda telah terdaftar pada <strong><?= esc($event['name']) ?></strong> kategori <strong><?= esc($category['name']) ?></strong>.
                </p>

                <div style="background-color: var(--color-canvas-soft); border: 1px dashed var(--color-mute); padding: var(--space-lg); border-radius: var(--radius-md); margin-bottom: var(--space-2xl);">
                    <div style="font-size: 14px; color: var(--color-body-mid); text-transform: uppercase; letter-spacing: 1px; font-weight: 500; margin-bottom: 8px;">Nomor BIB Anda</div>
                    <div style="font-size: 36px; font-weight: 700; font-family: var(--font-display); color: var(--color-primary);"><?= esc($registration['bib_number']) ?></div>
                </div>

                <!-- QR Code untuk Check-in -->
                <div style="margin-bottom: var(--space-2xl);">
                    <div style="font-size: 14px; color: var(--color-body-mid); text-transform: uppercase; letter-spacing: 1px; font-weight: 500; margin-bottom: var(--space-md);">QR Code Check-in</div>
                    <div style="background: white; display: inline-block; padding: 16px; border-radius: var(--radius-md); border: 1px solid var(--color-mute);">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($registration['qr_token']) ?>" alt="QR Code Check-in" width="200" height="200">
                    </div>
                    <p style="font-size: 13px; color: var(--color-body-mid); margin-top: var(--space-sm);">Tunjukkan QR ini ke panitia saat pengambilan Race Pack atau Check-in di lokasi event.</p>
                </div>

                <?php if ($registration['payment_status'] === 'unpaid'): ?>
                    <div style="text-align: left; background-color: #fffaf7; border: 1px solid #ffe0cc; padding: var(--space-md); border-radius: var(--radius-sm); margin-bottom: var(--space-2xl);">
                        <h4 style="color: var(--color-primary); margin-bottom: 8px; font-size: 18px;">Instruksi Pembayaran</h4>
                        <p style="font-size: 16px; margin-bottom: 8px;">Total yang harus dibayar: <strong>Rp <?= number_format($category['fee'], 0, ',', '.') ?></strong></p>
                        <p style="font-size: 14px; color: var(--color-body-mid);">Silakan transfer ke:<br>
                        <strong>Bank BCA - 1234567890 (a.n. Playon Brebes)</strong><br><br>
                        Setelah transfer, harap konfirmasi melalui WhatsApp Admin di <strong>0811-2222-3333</strong> dengan menyertakan Nomor BIB Anda.
                        </p>
                    </div>
                <?php endif; ?>

                <div>
                    <a href="<?= base_url('/') ?>" class="btn btn-primary" style="width: auto;">Kembali ke Beranda</a>
                </div>

            </div>

        </div>
    </section>
<?= $this->endSection() ?>
