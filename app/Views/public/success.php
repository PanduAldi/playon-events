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

                <?php if (!empty($email_status)): ?>
                    <div style="text-align: left; background-color: #e8f4ff; border: 1px solid #c6def8; padding: var(--space-md); border-radius: var(--radius-sm); margin-bottom: var(--space-2xl); color: #1f4f8b;">
                        <?= esc($email_status) ?>
                    </div>
                <?php endif; ?>

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
