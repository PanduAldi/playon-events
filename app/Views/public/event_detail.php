<?= $this->extend('public/layout') ?>

<?= $this->section('title') ?><?= esc($event['name']) ?> - Playon Events<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <style>
        .detail-header {
            background-color: var(--color-canvas);
            padding: var(--space-4xl) var(--space-xl) var(--space-xl);
            border-bottom: 1px solid var(--color-canvas-soft);
        }
        .detail-content {
            padding: var(--space-2xl) var(--space-xl);
        }
        .category-card {
            background-color: var(--color-canvas-soft);
            border: 1px solid var(--color-mute);
            border-radius: var(--radius-md);
            padding: var(--space-md);
            margin-bottom: var(--space-md);
        }
        .thumbnail-container {
            margin-top: var(--space-lg);
            text-align: center;
        }
        .event-thumbnail {
            width: 100%;
            max-height: 420px;
            border-radius: var(--radius-md);
            object-fit: cover;
            display: block;
            border: 1px solid var(--color-mute);
        }
        .event-thumbnail.portrait {
            width: auto;
            max-width: 100%;
            max-height: 600px;
            margin: 0 auto;
        }
        .event-thumbnail.landscape {
            width: 100%;
            max-height: 360px;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('nav_links') ?>
    <a href="<?= base_url('/') ?>" style="font-weight: 600;">Kembali ke Beranda</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <header class="detail-header">
        <div class="container">
            <?php if ($event['event_type'] === 'free'): ?>
                <span class="badge-pill">Gratis</span>
            <?php else: ?>
                <span class="badge-pill badge-paid">Berbayar</span>
            <?php endif; ?>
            <h1 style="font-size: 48px; margin-top: var(--space-sm); margin-bottom: var(--space-md);"><?= esc($event['name']) ?></h1>
            <div style="display: flex; gap: var(--space-xl); color: var(--color-body-mid);">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <?= date('d F Y', strtotime($event['event_date'])) ?>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    <?= esc($event['location']) ?>
                </div>
            </div>
        </div>
    </header>

    <section class="detail-content">
        <div class="container grid grid-2">
            <div>
                <h3 style="font-size: 24px; margin-bottom: var(--space-md);">Tentang Event</h3>
                <p style="margin-bottom: var(--space-xl); white-space: pre-wrap;"><?= esc($event['description']) ?></p>

                <?php if (!empty($event['banner_image'])): ?>
                    <div class="thumbnail-container">
                        <img id="eventThumbnail" class="event-thumbnail" src="<?= base_url('uploads/' . $event['banner_image']) ?>" alt="Banner <?= esc($event['name']) ?>">
                        <div id="thumbnailOrientationLabel" style="margin-top: var(--space-sm); color: var(--color-body-mid); font-size: 0.95rem;"></div>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <div class="card-content" style="position: sticky; top: 100px;">
                    <h3 class="card-title" style="margin-bottom: var(--space-md);">Pilih Kategori</h3>
                    
                    <?php if (session()->has('error')): ?>
                        <div style="padding: var(--space-md); border-radius: var(--radius-sm); margin-bottom: var(--space-lg); background-color: #ffebee; color: #c62828; border: 1px solid #ef9a9a;">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php 
                        $eventFull = true;
                        foreach ($categories as $cat) {
                            if ($cat['registered_count'] < $cat['max_participants']) {
                                $eventFull = false;
                                break;
                            }
                        }
                    ?>
                    
                    <?php foreach($categories as $cat): ?>
                        <?php $catTotal = $cat['total_registered'] ?? $cat['registered_count']; ?>
                        <?php $catFull = $catTotal >= $cat['max_participants']; ?>
                        <div class="category-card" style="<?= $catFull ? 'opacity: 0.6; background-color: #f5f5f5;' : '' ?>">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong><?= esc($cat['name']) ?></strong>
                                    <div style="font-size: 14px; color: var(--color-body-mid);">
                                        <?php if ($catFull): ?>
                                            <span style="color: #c62828; font-weight: bold;">Kuota Penuh</span>
                                        <?php else: ?>
                                            Sisa Kuota: <?= $cat['max_participants'] - $catTotal ?>
                                            <?php if (!empty($cat['max_individual'])): ?>
                                                <br>Kuota Pribadi: <?= max(0, $cat['max_individual'] - ($cat['individual_registered_count'] ?? 0)) ?> dari <?= $cat['max_individual'] ?>
                                            <?php endif; ?>
                                            <?php if (!empty($cat['max_community'])): ?>
                                                <br>Kuota Komunitas: <?= max(0, $cat['max_community'] - ($cat['community_registered_count'] ?? 0)) ?> dari <?= $cat['max_community'] ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div style="font-weight: 600; color: var(--color-ink);">
                                    <?= $cat['fee'] > 0 ? 'Rp ' . number_format($cat['fee'], 0, ',', '.') : 'Gratis' ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <div style="margin-top: var(--space-lg);">
                        <?php if ($eventFull): ?>
                            <button class="btn btn-primary" style="width: 100%; background-color: var(--color-mute); color: var(--color-body-mid); border-color: var(--color-mute); cursor: not-allowed;" disabled>Pendaftaran Penuh</button>
                        <?php else: ?>
                            <a href="<?= base_url('event/' . esc($event['slug']) . '/checkout') ?>" class="btn btn-primary" style="width: 100%; text-align: center; display: block;">Lanjut Pendaftaran</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var thumbnail = document.getElementById('eventThumbnail');
            if (!thumbnail) {
                return;
            }

            function applyOrientation() {
                if (!thumbnail.naturalWidth || !thumbnail.naturalHeight) {
                    return;
                }
                thumbnail.classList.remove('portrait', 'landscape');
                var orientationLabel = document.getElementById('thumbnailOrientationLabel');
                if (thumbnail.naturalWidth >= thumbnail.naturalHeight) {
                    thumbnail.classList.add('landscape');
                } else {
                    thumbnail.classList.add('portrait');
                }
            }

            if (thumbnail.complete) {
                applyOrientation();
            } else {
                thumbnail.addEventListener('load', applyOrientation);
            }
        });
    </script>
<?= $this->endSection() ?>
