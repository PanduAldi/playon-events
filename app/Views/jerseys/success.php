<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div style="max-width: 700px; margin: 0 auto; text-align: center; animation: fadeInUp 0.8s ease-out;">
        <div style="margin-bottom: var(--space-2xl);">
            <div class="success-icon-wrapper" style="width: 120px; height: 120px; background: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; border: 4px solid #fff; box-shadow: 0 10px 20px rgba(46, 125, 50, 0.1);">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h1 style="font-family: var(--font-display); font-weight: 800; font-size: clamp(2.5rem, 5vw, 3.5rem); color: var(--color-ink); line-height: 1.1; margin-bottom: 15px;">Pemesanan Berhasil!</h1>
            <p style="font-size: 1.25rem; color: var(--color-body-mid); line-height: 1.6;">Terima kasih telah berbelanja. Pesanan Anda telah kami terima dan sedang dalam antrean verifikasi.</p>
        </div>

        <div class="card-content" style="background: #fff; border: 2px solid var(--color-ink); border-radius: var(--radius-lg); padding: var(--space-2xl); margin-bottom: var(--space-3xl); box-shadow: 10px 10px 0px var(--color-ink); text-align: left;">
            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--color-mute); padding-bottom: 20px; margin-bottom: 20px;">
                <div>
                    <div style="font-size: 0.85rem; color: var(--color-body-mid); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Nomor Pesanan</div>
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--color-primary); font-family: var(--font-display);"><?= $order_number ?></div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 0.85rem; color: var(--color-body-mid); text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Status</div>
                    <span style="display: inline-block; padding: 4px 12px; background: #fff8e1; color: #f57f17; border-radius: var(--radius-pill); font-weight: 700; font-size: 0.8rem;">PENDING VERIFIKASI</span>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <h4 style="font-weight: 700; color: var(--color-ink); margin-bottom: 10px;">Langkah Selanjutnya:</h4>
                <ol style="padding-left: 20px; color: var(--color-body); line-height: 1.7;">
                    <li>Tim Admin kami akan memverifikasi bukti transfer Anda dalam waktu maksimal 24 jam.</li>
                    <li>Setelah verifikasi selesai, Anda akan menerima konfirmasi via Email/WhatsApp.</li>
                    <li>Pesanan akan segera dikemas dan dikirim ke alamat tujuan Anda.</li>
                </ol>
            </div>

            <div style="background: var(--color-canvas-soft); border-radius: var(--radius-md); padding: 15px; font-size: 0.9rem; color: var(--color-body-mid); display: flex; align-items: flex-start; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                <span>Simpan nomor pesanan ini atau ambil tangkapan layar (screenshot) sebagai bukti pemesanan Anda.</span>
            </div>
        </div>

        <div style="display: flex; gap: 15px; justify-content: center;">
            <a href="<?= base_url('/jerseys') ?>" class="btn btn-primary" style="padding: 15px 30px; font-weight: 700; font-size: 1.1rem;">KEMBALI KE STORE</a>
            <a href="<?= base_url('/') ?>" class="btn-cancel" style="padding: 15px 30px; font-weight: 700; font-size: 1.1rem; border: 2px solid var(--color-ink); background: transparent; border-radius: var(--radius-sm); text-decoration: none; color: var(--color-ink);">BERANDA</a>
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .success-icon-wrapper {
        animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes scaleIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }
</style>
<?= $this->endSection() ?>
