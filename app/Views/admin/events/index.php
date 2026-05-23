<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Event - Playon Admin</title>
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
            vertical-align: middle;
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
        .badge-danger { background-color: #ffebee; color: #c62828; }
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
            text-decoration: none;
            color: var(--color-ink);
        }
        .btn-sm:hover { background-color: var(--color-mute); }
        .btn-primary-sm {
            padding: 8px 16px;
            font-size: 14px;
            border-radius: var(--radius-sm);
            background-color: var(--color-primary);
            color: var(--color-on-primary);
            border: none;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary-sm:hover { opacity: 0.9; }
        .btn-danger-sm {
            padding: 4px 12px;
            font-size: 12px;
            border-radius: var(--radius-sm);
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
        }
        .btn-danger-sm:hover { background-color: #ffcdd2; }

        .banner-thumb {
            width: 80px;
            height: 45px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-mute);
        }
        .banner-placeholder {
            width: 80px;
            height: 45px;
            background-color: var(--color-canvas-soft);
            border-radius: var(--radius-sm);
            border: 1px dashed var(--color-mute);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: var(--color-body-mid);
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <div class="sidebar">
            <div class="sidebar-brand">Playon Admin</div>
            <nav style="flex-grow: 1;">
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-item">Dashboard</a>
                <a href="<?= base_url('admin/events') ?>" class="nav-item active">Events</a>
                <a href="<?= base_url('admin/participants') ?>" class="nav-item">Peserta</a>
                <a href="<?= base_url('admin/scanner') ?>" class="nav-item">Scanner</a>
            </nav>
            <div style="margin-top: auto;">
                <a href="<?= base_url('logout') ?>" class="nav-item" style="color: #ef9a9a;">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: var(--space-xl);">
                <div>
                    <h1 style="font-size: 32px; margin-bottom: var(--space-xs);">Daftar Event</h1>
                    <p style="color: var(--color-body-mid);">Kelola event lari dan unggah gambar banner.</p>
                </div>
                <a href="<?= base_url('admin/events/new') ?>" class="btn-primary-sm">Tambah Event</a>
            </div>

            <?php if (session()->has('success')): ?>
                <div style="padding: var(--space-md); background-color: #e8f5e9; color: #2e7d32; border-radius: var(--radius-sm); margin-bottom: var(--space-md);">
                    <?= session('success') ?>
                </div>
            <?php endif; ?>

            <div class="data-table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Banner</th>
                            <th>Nama Event</th>
                            <th>Tanggal Event</th>
                            <th>Lokasi</th>
                            <th>Tipe</th>
                            <th>Kuota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($events)): ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td>
                                        <?php if ($event['banner_image']): ?>
                                            <img src="<?= base_url('uploads/' . $event['banner_image']) ?>" class="banner-thumb" alt="Banner">
                                        <?php else: ?>
                                            <div class="banner-placeholder">No Banner</div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--color-ink);"><?= esc((string) $event['name']) ?></div>
                                        <div style="font-size: 12px; color: var(--color-mute);">slug: <?= esc((string) $event['slug']) ?></div>
                                    </td>
                                    <td><?= date('d M Y, H:i', strtotime((string) $event['event_date'])) ?></td>
                                    <td><?= esc((string) $event['location']) ?></td>
                                    <td>
                                        <?php if ($event['event_type'] === 'free'): ?>
                                            <span class="badge badge-success">Gratis</span>
                                        <?php else: ?>
                                            <span class="badge badge-info">Berbayar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= esc((string) ($event['quota'] > 0 ? $event['quota'] : 'Tak Terbatas')) ?>
                                    </td>
                                    <td>
                                        <?php if ($event['status'] === 'active'): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php elseif ($event['status'] === 'draft'): ?>
                                            <span class="badge badge-warning">Draft</span>
                                        <?php elseif ($event['status'] === 'closed'): ?>
                                            <span class="badge badge-dark">Tutup</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px;">
                                            <a href="<?= base_url('admin/events/edit/' . $event['id']) ?>" class="btn-sm">Edit</a>
                                            <a href="<?= base_url('admin/events/delete/' . $event['id']) ?>" class="btn-danger-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center; color: var(--color-body-mid);">Belum ada event lari.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>