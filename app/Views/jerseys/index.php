<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Jersey Store - Playon Brebes<?= $this->endSection() ?>

<?= $this->section('nav_links') ?>
<a href="<?= base_url('/') ?>" style="font-weight: 600; margin-right: 20px;">Beranda</a>
<a href="<?= base_url('/jerseys') ?>" style="font-weight: 600;">Jersey Store</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div style="text-align: center; margin-bottom: 4rem;">
        <h2 style="font-family: var(--font-display); font-weight: 800; font-size: clamp(2.5rem, 5vw, 4rem); line-height: 1; margin-bottom: 1rem; color: var(--color-ink);">Jersey Store</h2>
        <p style="color: var(--color-body-mid); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">Koleksi jersey eksklusif Playon Brebes. Kualitas premium untuk performa lari terbaik Anda.</p>
    </div>

    <div class="grid grid-3" style="gap: var(--space-2xl);">
        <?php foreach ($jerseys as $jersey): ?>
            <div class="card-content product-card" style="display: flex; flex-direction: column; height: 100%; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; border: 1px solid var(--color-canvas-soft);">
                <a href="<?= base_url('jerseys/' . $jersey['slug']) ?>" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%;">
                    <div style="margin: -24px -24px 20px -24px; overflow: hidden; border-top-left-radius: var(--radius-md); border-top-right-radius: var(--radius-md); position: relative; background-color: #f8f9fa;">
                        <img src="/uploads/jerseys/<?= $jersey['primary_image'] ?>" alt="<?= esc($jersey['name']) ?>" style="width: 100%; aspect-ratio: 1/1; object-fit: cover; display: block; transition: transform 0.5s ease;" class="product-image">
                        <div class="image-overlay" style="position: absolute; inset: 0; background: rgba(0,0,0,0.03); opacity: 0; transition: opacity 0.3s ease;"></div>
                    </div>

                    <div class="card-header" style="flex-grow: 1; padding: 0;">
                        <h3 class="card-title" style="font-size: 1.15rem; font-weight: 700; line-height: 1.4; margin-bottom: 8px; min-height: 3.2em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= esc($jersey['name']) ?></h3>
                        <div style="display: flex; align-items: baseline; gap: 8px;">
                            <span style="color: var(--color-primary); font-size: 1.35rem; font-weight: 800; font-family: var(--font-display);">
                                Rp <?= number_format($jersey['price'], 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>

                    <div class="card-footer" style="padding: 0; margin-top: 20px; border-top: 1px solid var(--color-canvas-soft); padding-top: 15px;">
                        <span class="btn btn-primary" style="width: 100%; text-align: center; display: block; background-color: var(--color-ink); border-color: var(--color-ink); font-weight: 700; letter-spacing: 0.5px;">LIHAT DETAIL</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        border-color: var(--color-primary) !important;
    }
    .product-card:hover .product-image {
        transform: scale(1.08);
    }
    .product-card:hover .image-overlay {
        opacity: 1;
    }
    .product-card:hover .btn-primary {
        background-color: var(--color-primary) !important;
        border-color: var(--color-primary) !important;
    }
</style>
<?= $this->endSection() ?>
