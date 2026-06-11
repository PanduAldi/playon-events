<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Manajemen Jersey - Playon Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Jersey Store<?= $this->endSection() ?>

<?= $this->section('page_description') ?><p>Kelola katalog jersey, stok per ukuran, dan foto produk.</p><?= $this->endSection() ?>

<?= $this->section('page_action') ?>
    <a href="<?= base_url('admin/jerseys/new') ?>" class="btn-primary-sm">Tambah Jersey</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="data-table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Jersey</th>
                    <th>Harga</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($jerseys)): ?>
                    <?php foreach ($jerseys as $jersey): ?>
                    <tr>
                        <td><?= $jersey['id'] ?></td>
                        <td style="font-weight: 700; color: var(--color-ink);"><?= esc($jersey['name']) ?></td>
                        <td>Rp <?= number_format($jersey['price'], 0, ',', '.') ?></td>
                        <td>
                            <?php if ($jersey['status'] === 'active'): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/jerseys/edit/' . $jersey['id']) ?>" class="btn-sm">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--color-body-mid);">Belum ada jersey yang ditambahkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?= $this->endSection() ?>
