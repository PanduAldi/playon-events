<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>Admin Dashboard - Playon<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Overview<?= $this->endSection() ?>

<?= $this->section('page_description') ?><p>Ringkas status event, peserta, dan okupansi saat ini.</p><?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .stat-card,
    .stat-card-dark {
        border-radius: var(--radius-md);
        padding: var(--space-xl);
        min-height: 150px;
    }

    .stat-card {
        background-color: var(--color-canvas);
        border: 1px solid var(--color-mute);
    }

    .stat-card-dark {
        background-color: var(--color-ink);
        color: var(--color-on-primary);
        border: none;
    }

    .stat-value {
        font-family: var(--font-display);
        font-size: 3rem;
        font-weight: 700;
        margin-top: var(--space-sm);
        line-height: 1;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: var(--space-xl);
        margin-top: var(--space-2xl);
        align-items: start;
    }

    .data-table-container h2 {
        margin: 0;
        font-size: 1.35rem;
    }

    .summary-row {
        display: grid;
        gap: var(--space-xl);
    }

    @media (max-width: 960px) {
        .content-grid,
        .summary-row {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="summary-row">
        <div class="stat-card">
            <div style="color: var(--color-body-mid); font-weight: 500;">Total Pendaftar</div>
            <div class="stat-value"><?= $total_participants ?></div>
        </div>
        <div class="stat-card">
            <div style="color: var(--color-body-mid); font-weight: 500;">Pendaftar Lunas</div>
            <div class="stat-value" style="color: var(--color-primary);"><?= $paid_participants ?></div>
        </div>
        <div class="stat-card-dark">
            <div style="color: var(--color-on-primary); font-weight: 500;">Event Aktif</div>
            <div class="stat-value"><?= $total_events ?></div>
        </div>
    </div>

    <div class="grid grid-3" style="margin-top: var(--space-xl); gap: var(--space-xl);">
        <div class="stat-card">
            <div style="color: var(--color-body-mid); font-weight: 500;">Sudah Check-in</div>
            <div class="stat-value"><?= $checked_in_participants ?></div>
        </div>
    </div>

    <div class="content-grid">
        <div class="data-table-container">
            <div style="padding: var(--space-lg); border-bottom: 1px solid var(--color-mute);">
                <h2>Okupansi Event</h2>
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
                <h2>Pendaftar Terbaru</h2>
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
<?= $this->endSection() ?>
