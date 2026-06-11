<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>PlayonBrebes Events - Tempat Lari Kumpul Bersama<?= $this->endSection() ?>

<?= $this->section('nav_links') ?>
<a href="<?= base_url('/') ?>" style="font-weight: 600; margin-right: 20px;">Home</a>
<a href="<?= base_url('/jerseys') ?>" style="font-weight: 600;">Jersey Store</a>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .hero-band {
        background-image: linear-gradient(rgba(255, 254, 251, 0.85), rgba(255, 254, 251, 0.85)), url('<?= base_url('assets/img/hero.jpg') ?>');
        background-size: cover;
        background-position: center;
        position: relative;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <header class="hero-band">
        <div class="container">
            <h1>Satu langkah awal, ribuan cerita bersama.</h1>
            <p>Platform event lari  komunitas Playon Brebes. Temukan berbagai event lari dan bergabunglah bersama kami.</p>
        </div>
    </header>

    <section class="content-band-cream">
        <div class="container">
            <h2 class="section-title">Event Lari Aktif</h2>
            
            <div class="grid grid-3">
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <div class="card-content">
                            <?php if ($event['banner_image']): ?>
                                <div style="margin: -24px -24px 24px -24px; overflow: hidden; border-top-left-radius: var(--radius-md); border-top-right-radius: var(--radius-md);">
                                    <img src="<?= base_url('uploads/' . esc($event['banner_image'])) ?>" alt="<?= esc($event['name']) ?>" style="width: 100%; aspect-ratio: 16/9; object-fit: cover; display: block;">
                                </div>
                            <?php endif; ?>
                            
                            <div class="card-header">
                                <?php if ($event['event_type'] === 'free'): ?>
                                    <span class="badge-pill">Gratis</span>
                                <?php else: ?>
                                    <span class="badge-pill badge-paid">Berbayar</span>
                                <?php endif; ?>
                                
                                <h3 class="card-title"><?= esc($event['name']) ?></h3>
                                <div class="card-date">
                                    <!-- Icon Calendar -->
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                    <?= date('d M Y, H:i', strtotime($event['event_date'])) ?>
                                </div>
                                <div class="card-date" style="margin-top: 4px;">
                                    <!-- Icon Map Pin -->
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    <?= esc($event['location']) ?>
                                </div>
                            </div>
                            
                            <p style="margin-bottom: 24px; flex-grow: 1; color: var(--color-body);">
                                <?= esc($event['description']) ?>
                            </p>

                            <!-- Kuota info -->
                            <div style="margin-bottom: 12px; font-size: 14px;">
                                <strong style="color: var(--color-ink);">Kuota Kategori:</strong>
                                <ul style="margin: 4px 0 0 0; padding-left: 16px; color: var(--color-body-mid);">
                                    <?php 
                                        $eventFull = true;
                                        foreach($event['categories'] as $cat) {
                                            $catFull = $cat['registered_count'] >= $cat['max_participants'];
                                            if (!$catFull) {
                                                $eventFull = false;
                                            }
                                            ?>
                                            <li>
                                                <?= esc($cat['name']) ?>: 
                                                <span style="color: <?= $catFull ? '#c62828' : 'var(--color-primary)' ?>; font-weight: 600;">
                                                    <?= $cat['registered_count'] ?> / <?= $cat['max_participants'] ?>
                                                    <?= $catFull ? '(Penuh)' : '' ?>
                                                </span>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                            
                            <!-- Kategori info -->
                            <div style="margin-bottom: 24px; font-size: 14px; padding-top: 16px; border-top: 1px solid var(--color-canvas-soft);">
                                <strong style="color: var(--color-ink);">Kategori:</strong> 
                                <?php 
                                    $cats = [];
                                    foreach($event['categories'] as $cat) {
                                        $cats[] = esc($cat['name']);
                                    }
                                    echo implode(', ', $cats);
                                ?>
                            </div>
                            
                            <div class="card-footer">
                                <?php if ($eventFull): ?>
                                    <button class="btn btn-primary" style="background-color: var(--color-mute); color: var(--color-body-mid); border-color: var(--color-mute); cursor: not-allowed; width: 100%;" disabled>Penuh</button>
                                <?php else: ?>
                                    <a href="<?= base_url('event/' . esc($event['slug'])) ?>" class="btn btn-primary">Daftar Sekarang</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center; grid-column: 1 / -1; color: var(--color-body-mid);">Belum ada event lari yang aktif saat ini.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
