<?= $this->extend('admin/auth_layout') ?>

<?= $this->section('title') ?>Login Admin - Playon<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div style="text-align: center; margin-bottom: var(--space-2xl);">
        <h1>Playon Admin</h1>
        <p style="color: var(--color-body-mid); font-size: 16px;">Masuk ke panel manajemen event</p>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert"><?= session('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post">
        <?= csrf_field() ?>
        <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Masukkan password admin" required>
        <button type="submit" class="btn-primary">Masuk Dashboard</button>
    </form>
<?= $this->endSection() ?>
