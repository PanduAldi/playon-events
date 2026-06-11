<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Pendaftaran Komunitas Berhasil - <?= esc($event['name']) ?><?= $this->endSection() ?>

<?= $this->section('nav_links') ?>
    <a href="<?= base_url('/') ?>" class="btn btn-text" style="font-weight: 600;">Beranda</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section style="padding: var(--space-4xl) 0; background-color: var(--color-canvas-soft); min-height: calc(100vh - 70px); display: flex; align-items: center; justify-content: center;">
        <div class="container" style="max-width: 640px; width: 100%;">
            <div class="card-content" style="background-color: var(--color-canvas); text-align: center; padding: var(--space-3xl) var(--space-xl);">
                <div style="width: 64px; height: 64px; background-color: #e8f5e9; color: #2e7d32; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                <h1 style="font-size: 32px; margin-bottom: var(--space-sm);">Pendaftaran Komunitas Berhasil!</h1>
                <p style="color: var(--color-body); margin-bottom: var(--space-2xl);">
                    Komunitas Anda berhasil didaftarkan pada event <strong><?= esc($event['name']) ?></strong> kategori <strong><?= esc($category['name']) ?></strong>.
                </p>
                <p style="font-size: 16px; color: var(--color-body-mid); margin-bottom: var(--space-lg);">
                    Jumlah pelari terdaftar: <strong><?= esc($count) ?></strong>.
                </p>
                <?php if (!empty($email_status)): ?>
                    <div style="text-align: left; background-color: #e8f4ff; border: 1px solid #c6def8; padding: var(--space-md); border-radius: var(--radius-sm); margin-bottom: var(--space-2xl); color: #1f4f8b;">
                        <?= esc($email_status) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <a href="<?= base_url('/') ?>" class="btn btn-primary" style="width: auto;">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
