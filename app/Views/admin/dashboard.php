<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Playon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        .app-shell {
            display: flex;
            min-height: 100vh;
        }
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
        .nav-item.active {
            border-left: 3px solid var(--color-primary);
        }
        .main-content {
            flex: 1;
            background-color: var(--color-canvas-soft);
            padding: var(--space-3xl) var(--space-2xl);
            overflow-y: auto;
        }
        .stat-card {
            background-color: var(--color-canvas);
            padding: var(--space-xl);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-mute);
        }
        .stat-card-dark {
            background-color: var(--color-ink);
            color: var(--color-on-primary);
            padding: var(--space-xl);
            border-radius: var(--radius-md);
        }
        .stat-value {
            font-family: var(--font-display);
            font-size: 48px;
            font-weight: 600;
            margin-top: var(--space-sm);
        }
        .data-table-container {
            background-color: var(--color-canvas);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-mute);
            overflow: hidden;
            margin-top: var(--space-2xl);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
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
        }
        tr:last-child td {
            border-bottom: none;
        }
        .badge {
            padding: 4px 8px;
            border-radius: var(--radius-pill);
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background-color: #e8f5e9; color: #2e7d32; }
        .badge-warning { background-color: #fff8e1; color: #f57f17; }
        .badge-dark { background-color: var(--color-ink); color: var(--color-on-primary); }
        .progress-track {
            height: 10px;
            overflow: hidden;
            border-radius: var(--radius-pill);
            background-color: var(--color-canvas-soft);
            border: 1px solid var(--color-mute);
        }
        .progress-bar {
            height: 100%;
            background-color: var(--color-primary);
            border-radius: var(--radius-pill);
        }
        .content-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: var(--space-xl);
            margin-top: var(--space-2xl);
            align-items: start;
        }
        @media (max-width: 960px) {
            .sidebar { display: none; }
            .content-grid { grid-template-columns: 1fr; }
            .main-content { padding: var(--space-xl); }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <div class="sidebar">
            <div class="sidebar-brand">Playon Admin</div>
            <nav style="flex-grow: 1;">
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-item active">Dashboard</a>
                <a href="<?= base_url('admin/events') ?>" class="nav-item">Events</a>
                <a href="<?= base_url('admin/participants') ?>" class="nav-item">Peserta</a>
                <a href="<?= base_url('admin/scanner') ?>" class="nav-item">Scanner</a>
            </nav>
            <div style="margin-top: auto;">
                <a href="<?= base_url('logout') ?>" class="nav-item" style="color: #ef9a9a;">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <h1 style="font-size: 32px; margin-bottom: var(--space-2xl);">Overview</h1>
            
            <div class="grid grid-3">
                <div class="stat-card">
                    <div style="color: var(--color-body-mid); font-weight: 500;">Total Pendaftar</div>
                    <div class="stat-value"><?= $total_participants ?></div>
                </div>
                <div class="stat-card">
                    <div style="color: var(--color-body-mid); font-weight: 500;">Pendaftar Lunas</div>
                    <div class="stat-value" style="color: var(--color-primary);"><?= $paid_participants ?></div>
                </div>
                <div class="stat-card-dark">
                    <div style="color: var(--color-mute); font-weight: 500;">Event Aktif</div>
                    <div class="stat-value"><?= $total_events ?></div>
                </div>
            </div>

            <div class="grid grid-3" style="margin-top: var(--space-xl);">
                <div class="stat-card">
                    <div style="color: var(--color-body-mid); font-weight: 500;">Sudah Check-in</div>
                    <div class="stat-value"><?= $checked_in_participants ?></div>
                </div>
            </div>

            <div class="content-grid">
                <div class="data-table-container">
                    <div style="padding: var(--space-lg); border-bottom: 1px solid var(--color-mute);">
                        <h2 style="font-size: 22px;">Okupansi Event</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Event</th>
                                <th>Kuota</th>
                                <th>Terisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($events)): ?>
                                <?php foreach ($events as $event): ?>
                                    <?php
                                        $max = (int) $event['max_participants'];
                                        $filled = (int) $event['registered_count'];
                                        $percent = $max > 0 ? min(100, round(($filled / $max) * 100)) : 0;
                                    ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight: 700; color: var(--color-ink);"><?= esc($event['name']) ?></div>
                                            <div style="font-size: 13px; color: var(--color-body-mid);"><?= date('d M Y', strtotime($event['event_date'])) ?></div>
                                        </td>
                                        <td><?= $filled ?> / <?= $max ?></td>
                                        <td>
                                            <div class="progress-track" aria-label="Okupansi <?= $percent ?> persen">
                                                <div class="progress-bar" style="width: <?= $percent ?>%;"></div>
                                            </div>
                                            <div style="font-size: 12px; color: var(--color-body-mid); margin-top: 4px;"><?= $percent ?>%</div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; color: var(--color-body-mid);">Belum ada event aktif atau ditutup.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="data-table-container">
                    <div style="padding: var(--space-lg); border-bottom: 1px solid var(--color-mute);">
                        <h2 style="font-size: 22px;">Pendaftar Terbaru</h2>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Peserta</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_registrations)): ?>
                                <?php foreach ($recent_registrations as $row): ?>
                                    <tr>
                                        <td>
                                            <div style="font-weight: 700; color: var(--color-ink);"><?= esc($row['full_name']) ?></div>
                                            <div style="font-size: 13px; color: var(--color-body-mid);"><?= esc($row['event_name']) ?> - <?= esc($row['category_name']) ?></div>
                                        </td>
                                        <td>
                                            <?php if ($row['payment_status'] === 'paid' || $row['payment_status'] === 'free'): ?>
                                                <span class="badge badge-success">OK</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Belum Lunas</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" style="text-align: center; color: var(--color-body-mid);">Belum ada pendaftar.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
