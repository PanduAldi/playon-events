<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Peserta - Playon Admin</title>
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
        .data-table-container {
            background-color: var(--color-canvas);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-mute);
            overflow-x: auto;
            margin-top: var(--space-xl);
        }
        table { width: 100%; border-collapse: collapse; min-width: 800px; }
        th {
            background-color: var(--color-canvas-soft);
            text-align: left;
            padding: var(--space-md) var(--space-lg);
            font-family: var(--font-display);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--color-ink-mid);
            border-bottom: 1px solid var(--color-mute);
        }
        td {
            padding: var(--space-md) var(--space-lg);
            border-bottom: 1px solid var(--color-canvas-soft);
            font-size: 15px;
            color: var(--color-ink);
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #faf8f5; }
        
        .badge {
            padding: 4px 8px;
            border-radius: var(--radius-pill);
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }
        .badge-success { background-color: #e8f5e9; color: #2e7d32; }
        .badge-warning { background-color: #fff8e1; color: #f57f17; }
        .badge-dark { background-color: var(--color-ink); color: var(--color-on-primary); }
        .badge-info { background-color: #e3f2fd; color: #1565c0; }

        .btn-sm {
            padding: 4px 12px;
            font-size: 12px;
            border-radius: var(--radius-sm);
            background-color: var(--color-canvas-soft);
            border: 1px solid var(--color-mute);
            cursor: pointer;
            font-weight: 600;
        }
        .btn-sm:hover { background-color: var(--color-mute); }
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
        .status-select {
            min-width: 130px;
            padding: 6px 8px;
            border: 1px solid var(--color-mute);
            border-radius: var(--radius-sm);
            background-color: var(--color-canvas);
            font-family: var(--font-body);
            font-size: 13px;
        }
        .action-row {
            display: flex;
            gap: var(--space-xs);
            flex-wrap: wrap;
        }
        @media (max-width: 1000px) {
            .sidebar { display: none; }
            .filters { grid-template-columns: 1fr; }
            .main-content { padding: var(--space-xl); }
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
                <a href="<?= base_url('admin/participants') ?>" class="nav-item active">Peserta</a>
                <a href="<?= base_url('admin/scanner') ?>" class="nav-item">Scanner</a>
            </nav>
            <div style="margin-top: auto;">
                <a href="<?= base_url('logout') ?>" class="nav-item" style="color: #ef9a9a;">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--space-xl);">
                <div>
                    <h1 style="font-size: 32px; margin-bottom: var(--space-xs);">Daftar Peserta</h1>
                    <p style="color: var(--color-body-mid);">Kelola pendaftar event dan validasi pembayaran.</p>
                </div>
                <a href="<?= base_url('admin/participants/export') . '?' . http_build_query($filters) ?>" class="btn-sm" style="padding: 10px 14px;">Export CSV</a>
            </div>

            <?php if (session()->has('success')): ?>
                <div style="padding: var(--space-md); background-color: #e8f5e9; color: #2e7d32; border-radius: var(--radius-sm); margin-bottom: var(--space-md);">
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
                <button type="submit" class="btn-sm" style="height: 42px;">Terapkan</button>
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
                        <?php if(!empty($registrations)): ?>
                            <?php foreach($registrations as $reg): ?>
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
                                    <?php
                                        $statusLabel = [
                                            'pending' => 'Pending',
                                            'confirmed' => 'Confirmed',
                                            'attended' => 'Hadir',
                                            'cancelled' => 'Batal',
                                        ][$reg['status']] ?? $reg['status'];
                                    ?>
                                    <span class="badge <?= $reg['status'] === 'attended' ? 'badge-success' : ($reg['status'] === 'cancelled' ? 'badge-dark' : 'badge-info') ?>">
                                        <?= esc($statusLabel) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-row">
                                    <?php if ($reg['payment_status'] === 'unpaid'): ?>
                                    <form action="<?= base_url('admin/participants/update-status') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="reg_id" value="<?= $reg['reg_id'] ?>">
                                        <input type="hidden" name="payment_status" value="paid">
                                        <button type="submit" class="btn-sm" onclick="return confirm('Tandai sebagai lunas?');">Set Lunas</button>
                                    </form>
                                    <?php endif; ?>
                                    <form action="<?= base_url('admin/participants/update-status') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="reg_id" value="<?= $reg['reg_id'] ?>">
                                        <select name="status" class="status-select" onchange="this.form.submit()">
                                            <option value="pending" <?= $reg['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="confirmed" <?= $reg['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                            <option value="attended" <?= $reg['status'] === 'attended' ? 'selected' : '' ?>>Hadir</option>
                                            <option value="cancelled" <?= $reg['status'] === 'cancelled' ? 'selected' : '' ?>>Batal</option>
                                        </select>
                                    </form>
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

        </div>
    </div>
</body>
</html>
