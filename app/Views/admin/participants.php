<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>Manajemen Peserta - Playon Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Daftar Peserta<?= $this->endSection() ?>

<?= $this->section('page_description') ?><p>Kelola pendaftar event dan validasi pembayaran dengan cepat.</p><?= $this->endSection() ?>

<?= $this->section('page_action') ?>
    <a href="<?= base_url('admin/participants/export') . '?' . http_build_query($filters) ?>" class="btn-sm">Export CSV</a>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .filters {
        display: grid;
        grid-template-columns: 1.2fr repeat(3, 0.8fr) auto;
        gap: var(--space-sm);
        align-items: end;
        margin-bottom: var(--space-xl);
    }

    .filter-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--color-mute);
        border-radius: var(--radius-sm);
        background-color: var(--color-canvas);
        color: var(--color-ink);
        font-family: var(--font-body);
        font-size: 14px;
    }

    .filter-label {
        display: block;
        margin-bottom: 6px;
        color: var(--color-body-mid);
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .action-row {
        display: flex;
        gap: var(--space-xs);
        flex-wrap: wrap;
    }

    @media (max-width: 1000px) {
        .filters {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/participants') ?>" method="get" class="filters">
        <div>
            <label class="filter-label">Cari</label>
            <input type="search" name="q" class="filter-control" value="<?= esc($filters['q'] ?? '') ?>" placeholder="Nama, email, telepon, BIB">
        </div>
        <div>
            <label class="filter-label">Event</label>
            <select name="event_id" class="filter-control">
                <option value="">Semua event</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?= $event['id'] ?>" <?= (string) ($filters['event_id'] ?? '') === (string) $event['id'] ? 'selected' : '' ?>>
                        <?= esc($event['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="filter-label">Pembayaran</label>
            <select name="payment_status" class="filter-control">
                <option value="">Semua</option>
                <option value="unpaid" <?= ($filters['payment_status'] ?? '') === 'unpaid' ? 'selected' : '' ?>>Belum lunas</option>
                <option value="paid" <?= ($filters['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>Lunas</option>
                <option value="free" <?= ($filters['payment_status'] ?? '') === 'free' ? 'selected' : '' ?>>Gratis</option>
            </select>
        </div>
        <div>
            <label class="filter-label">Status</label>
            <select name="status" class="filter-control">
                <option value="">Semua</option>
                <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="attended" <?= ($filters['status'] ?? '') === 'attended' ? 'selected' : '' ?>>Hadir</option>
                <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Batal</option>
            </select>
        </div>
        <button type="submit" class="btn-sm">Terapkan</button>
    </form>

    <div class="data-table-container">
        <table>
            <thead>
                <tr>
                    <th>BIB / Tanggal</th>
                    <th>Peserta</th>
                    <th>Event & Kategori</th>
                    <th>Status Pembayaran</th>
                    <th>Status Peserta</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($registrations)): ?>
                    <?php foreach ($registrations as $reg): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 600; font-family: var(--font-display);"><?= esc($reg['bib_number']) ?></div>
                                <div style="font-size: 13px; color: var(--color-body-mid);"><?= date('d M Y H:i', strtotime($reg['registered_at'])) ?></div>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?= esc($reg['full_name']) ?></div>
                                <div style="font-size: 13px; color: var(--color-body-mid);"><?= esc($reg['phone']) ?></div>
                                <div style="font-size: 13px; color: var(--color-body-mid);"><?= esc($reg['email']) ?></div>
                            </td>
                            <td>
                                <div><?= esc($reg['event_name']) ?></div>
                                <div style="font-size: 13px; color: var(--color-body-mid);"><?= esc($reg['category_name']) ?> (<?= $reg['fee'] == 0 ? 'Gratis' : 'Rp ' . number_format($reg['fee'],0,',','.') ?>)</div>
                            </td>
                            <td>
                                <?php if ($reg['payment_status'] === 'free'): ?>
                                    <span class="badge badge-info">Gratis</span>
                                <?php elseif ($reg['payment_status'] === 'paid'): ?>
                                    <span class="badge badge-success">Lunas</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">Belum Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= esc($reg['status'] === 'attended' ? 'success' : ($reg['status'] === 'pending' ? 'warning' : ($reg['status'] === 'cancelled' ? 'danger' : 'info'))) ?>">
                                    <?= esc(ucfirst($reg['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-row">
                                    <a href="<?= base_url('admin/participants/view/' . $reg['id']) ?>" class="btn-sm">Detail</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--color-body-mid);">Belum ada pendaftar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?= $this->endSection() ?>
