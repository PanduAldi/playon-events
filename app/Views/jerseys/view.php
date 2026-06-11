<?= $this->extend('layouts/main') ?>


<?= $this->section('nav_links') ?>
<a href="<?= base_url('/') ?>" style="font-weight: 600; margin-right: 20px;">Beranda</a>
<a href="<?= base_url('/jerseys') ?>" style="font-weight: 600;">Jersey Store</a>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container py-5">
    <div class="grid grid-2" style="gap: var(--space-4xl); align-items: start;">

        <!-- Image Gallery Column -->
        <div class="gallery-container" style="position: sticky; top: 120px;">
            <!-- Main Image Display -->
            <div class="main-image-wrapper" style="border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--color-mute); margin-bottom: var(--space-lg); background-color: #fff; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                <?php if (count($images) > 0): ?>
                    <img id="mainImage" src="/uploads/jerseys/<?= $images[0]['image_path'] ?>" alt="<?= esc($jersey['name']) ?>" style="width: 100%; aspect-ratio: 1/1; object-fit: contain; display: block; transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                <?php else: ?>
                    <div style="width: 100%; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; color: var(--color-body-mid); background-color: #f8f9fa;">
                        Belum ada foto produk
                    </div>
                <?php endif; ?>
            </div>

            <!-- Thumbnail List -->
            <?php if (count($images) > 1): ?>
                <div class="thumbnails-wrapper" style="display: flex; gap: var(--space-md); overflow-x: auto; padding: 4px; scrollbar-width: none;">
                    <?php foreach ($images as $index => $img): ?>
                        <div class="thumbnail-item" onclick="changeMainImage('/uploads/jerseys/<?= $img['image_path'] ?>', this)" style="width: 80px; height: 80px; flex-shrink: 0; border-radius: var(--radius-sm); overflow: hidden; cursor: pointer; border: 3px solid <?= $index === 0 ? 'var(--color-primary)' : 'transparent' ?>; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                            <img src="/uploads/jerseys/<?= $img['image_path'] ?>" alt="Thumbnail <?= $index + 1 ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Details & Order Form Column -->
        <div class="details-container">
            <?php if (session()->getFlashdata('error')): ?>
                <div style="padding: var(--space-md); border-radius: var(--radius-sm); margin-bottom: var(--space-lg); background-color: #ffebee; color: #c62828; border: 1px solid #ef9a9a; display: flex; align-items: center; gap: 10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <div style="margin-bottom: var(--space-3xl); border-bottom: 1px solid var(--color-canvas-soft); padding-bottom: var(--space-xl);">
                <nav style="margin-bottom: var(--space-md); font-size: 0.9rem; color: var(--color-body-mid);">
                    <a href="<?= base_url('/jerseys') ?>" style="color: inherit; text-decoration: none;">Jersey Store</a>
                    <span style="margin: 0 8px;">/</span>
                    <span style="color: var(--color-ink); font-weight: 500;"><?= esc($jersey['name']) ?></span>
                </nav>

                <h1 style="font-family: var(--font-display); font-weight: 800; font-size: clamp(2.5rem, 4vw, 3.5rem); line-height: 1.1; margin-bottom: var(--space-sm); color: var(--color-ink);">
                    <?= esc($jersey['name']) ?>
                </h1>

                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="color: var(--color-primary); font-size: 2.25rem; font-weight: 800; font-family: var(--font-display);">
                        Rp <?= number_format($jersey['price'], 0, ',', '.') ?>
                    </div>
                    <span style="padding: 4px 12px; background: #e8f5e9; color: #2e7d32; border-radius: var(--radius-pill); font-weight: 700; font-size: 0.85rem; letter-spacing: 0.5px;">STOK TERSEDIA</span>
                </div>
            </div>

            <div style="margin-bottom: var(--space-3xl);">
                <h3 style="font-size: 1.35rem; font-weight: 700; margin-bottom: var(--space-md); color: var(--color-ink); display: flex; align-items: center; gap: 10px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    Informasi Produk
                </h3>
                <div style="color: var(--color-body); line-height: 1.8; white-space: pre-wrap; font-size: 1.05rem; background: #fff; padding: var(--space-lg); border-radius: var(--radius-md); border: 1px solid var(--color-canvas-soft);"><?= esc($jersey['description']) ?></div>
            </div>

            <!-- Order Form -->
            <form action="/jerseys/checkout" method="POST" enctype="multipart/form-data" class="card-content" style="background-color: #fff; border: 2px solid var(--color-ink); padding: var(--space-2xl); border-radius: var(--radius-lg); box-shadow: 10px 10px 0px var(--color-ink);">
                <?= csrf_field() ?>
                <input type="hidden" name="jersey_id" value="<?= $jersey['id'] ?>">

                <h3 style="font-size: 1.75rem; font-weight: 800; margin-bottom: var(--space-xl); color: var(--color-ink); text-transform: uppercase; letter-spacing: 1px;">Formulir Pemesanan</h3>

                <!-- Size Selection -->
                <div style="margin-bottom: var(--space-2xl);">
                    <label style="display: block; font-weight: 700; margin-bottom: var(--space-md); color: var(--color-ink); font-size: 1.1rem;">1. Pilih Ukuran <span style="color: var(--color-primary);">*</span></label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: var(--space-md);">
                        <?php foreach ($sizes as $size): ?>
                            <?php $isOutOfStock = $size['stock'] <= 0; ?>
                            <div style="position: relative;">
                                <input type="radio" name="size_id" id="size_<?= $size['id'] ?>" value="<?= $size['id'] ?>" <?= $isOutOfStock ? 'disabled' : '' ?> required style="display: none;" class="size-selector">
                                <label for="size_<?= $size['id'] ?>" style="
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    padding: 15px 10px;
                                    border: 2px solid var(--color-mute);
                                    border-radius: var(--radius-md);
                                    cursor: <?= $isOutOfStock ? 'not-allowed' : 'pointer' ?>;
                                    background-color: <?= $isOutOfStock ? '#f5f5f5' : '#fff' ?>;
                                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                    position: relative;
                                ">
                                    <div style="font-weight: 800; font-size: 1.25rem; color: <?= $isOutOfStock ? '#ccc' : 'var(--color-ink)' ?>;"><?= esc($size['size_name']) ?></div>
                                    <div style="font-size: 0.75rem; margin-top: 4px; font-weight: 600; color: <?= $isOutOfStock ? '#ccc' : 'var(--color-body-mid)' ?>;">
                                        <?= $isOutOfStock ? 'HABIS' : $size['stock'] . ' PCS' ?>
                                    </div>
                                    <?php if (!$isOutOfStock): ?>
                                        <div class="check-icon" style="position: absolute; top: -10px; right: -10px; background: var(--color-primary); color: white; width: 24px; height: 24px; border-radius: 50%; display: none; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        </div>
                                    <?php endif; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Quantity & Info -->
                <div style="display: grid; grid-template-columns: 1fr; gap: var(--space-xl); margin-bottom: var(--space-2xl);">
                    <div>
                        <label style="display: block; font-weight: 700; margin-bottom: var(--space-md); color: var(--color-ink); font-size: 1.1rem;">2. Jumlah Pesanan <span style="color: var(--color-primary);">*</span></label>
                        <div style="display: flex; align-items: center; max-width: 150px; border: 2px solid var(--color-mute); border-radius: var(--radius-md); overflow: hidden;">
                            <button type="button" onclick="this.nextElementSibling.stepDown(); this.nextElementSibling.dispatchEvent(new Event('change'))" style="padding: 10px 15px; border: none; background: #f8f9fa; cursor: pointer; font-weight: 800;">-</button>
                            <input type="number" name="quantity" min="1" value="1" required style="width: 100%; border: none; text-align: center; font-weight: 700; font-size: 1.1rem; padding: 10px 0; -moz-appearance: textfield;">
                            <button type="button" onclick="this.previousElementSibling.stepUp(); this.previousElementSibling.dispatchEvent(new Event('change'))" style="padding: 10px 15px; border: none; background: #f8f9fa; cursor: pointer; font-weight: 800;">+</button>
                        </div>
                    </div>
                </div>

                <!-- Customer Details -->
                <div style="margin-bottom: var(--space-2xl);">
                    <label style="display: block; font-weight: 700; margin-bottom: var(--space-md); color: var(--color-ink); font-size: 1.1rem;">3. Data Lengkap Penerima <span style="color: var(--color-primary);">*</span></label>
                    <div style="display: grid; gap: 15px;">
                        <div class="input-wrapper">
                            <input type="text" name="customer_name" placeholder="Nama Lengkap" required style="width: 100%; padding: 14px; border: 2px solid var(--color-mute); border-radius: var(--radius-md); font-size: 1rem; transition: all 0.3s ease;">
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <input type="email" name="customer_email" placeholder="Email" required style="width: 100%; padding: 14px; border: 2px solid var(--color-mute); border-radius: var(--radius-md); font-size: 1rem;">
                            <input type="text" name="customer_phone" placeholder="No. WhatsApp (08...)" required style="width: 100%; padding: 14px; border: 2px solid var(--color-mute); border-radius: var(--radius-md); font-size: 1rem;">
                        </div>
                        <textarea name="shipping_address" placeholder="Alamat Pengiriman Lengkap (Nama Jalan, No. Rumah, RT/RW, Kecamatan, Kota, Kodepos)" rows="4" required style="width: 100%; padding: 14px; border: 2px solid var(--color-mute); border-radius: var(--radius-md); resize: vertical; font-size: 1rem;"></textarea>
                    </div>
                </div>

                <!-- Payment Proof -->
                <div style="margin-bottom: var(--space-3xl);">
                    <label style="display: block; font-weight: 700; margin-bottom: var(--space-md); color: var(--color-ink); font-size: 1.1rem;">4. Pembayaran <span style="color: var(--color-primary);">*</span></label>

                    <div style="background-color: var(--color-ink); color: white; border-radius: var(--radius-md); padding: var(--space-xl); margin-bottom: var(--space-lg); position: relative; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                        <div style="position: relative; z-index: 2;">
                            <div style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 2px; opacity: 0.8; margin-bottom: 5px;">Transfer Ke Rekening</div>
                            <div style="font-size: 1.75rem; font-weight: 800; letter-spacing: 1px; margin-bottom: 5px;">1234567890</div>
                            <div style="font-size: 1.1rem; font-weight: 600;">Bank BCA</div>
                            <div style="font-size: 0.9rem; opacity: 0.8; margin-top: 5px;">a/n Playon Brebes</div>
                        </div>
                        <svg style="position: absolute; right: -20px; bottom: -20px; color: rgba(255,255,255,0.05); width: 150px; height: 150px;" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm4.59-12.42L10 14.17l-2.59-2.58L6 13l4 4 8-8z"></path></svg>
                    </div>

                    <div style="position: relative; border: 2px dashed var(--color-mute); border-radius: var(--radius-md); padding: var(--space-xl); text-align: center; background: #fafafa; transition: all 0.3s ease;" id="dropZone">
                        <input type="file" name="payment_proof" id="payment_proof" accept="image/*" required style="position: absolute; inset: 0; opacity: 0; cursor: pointer; z-index: 10;">
                        <div id="fileInfo">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--color-body-mid)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 10px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                            <div style="font-weight: 700; color: var(--color-ink); margin-bottom: 5px;">Upload Bukti Transfer</div>
                            <div style="font-size: 0.85rem; color: var(--color-body-mid);">Klik atau seret file gambar ke sini</div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.25rem; padding: 18px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; box-shadow: 0 10px 20px rgba(var(--color-primary-rgb), 0.3); transform: perspective(1px) translateZ(0); transition: all 0.3s ease;">
                    KONFIRMASI PEMESANAN
                </button>
                <p style="text-align: center; margin-top: 15px; font-size: 0.85rem; color: var(--color-body-mid);">
                    Dengan menekan tombol di atas, Anda setuju dengan Syarat & Ketentuan kami.
                </p>
            </form>
        </div>
    </div>
</div>

<style>
    /* Size Selector Logic */
    .size-selector:checked + label {
        border-color: var(--color-primary) !important;
        background-color: #fff !important;
        box-shadow: 0 5px 15px rgba(var(--color-primary-rgb), 0.15);
        transform: translateY(-2px);
    }
    .size-selector:checked + label .check-icon {
        display: flex !important;
    }

    .size-selector:not(:disabled) + label:hover {
        border-color: var(--color-primary);
        transform: translateY(-2px);
    }

    /* Input Focus */
    input:focus, textarea:focus {
        border-color: var(--color-primary) !important;
        background-color: #fff !important;
    }

    #dropZone:hover {
        border-color: var(--color-primary);
        background-color: #f0f7ff;
    }

    /* Gallery Animation */
    .gallery-container {
        animation: slideInLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .details-container {
        animation: slideInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes slideInLeft {
        from { opacity: 0; transform: translateX(-30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .gallery-container {
            position: static !important;
            margin-bottom: var(--space-2xl);
        }
        .grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    function changeMainImage(src, element) {
        const mainImg = document.getElementById('mainImage');
        mainImg.style.opacity = '0';
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 200);

        const thumbnails = document.querySelectorAll('.thumbnail-item');
        thumbnails.forEach(thumb => {
            thumb.style.borderColor = 'transparent';
        });
        element.style.borderColor = 'var(--color-primary)';
    }

    // File input preview
    document.getElementById('payment_proof').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name;
        if (fileName) {
            document.getElementById('fileInfo').innerHTML = `
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#2e7d32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 10px;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <div style="font-weight: 700; color: #2e7d32; margin-bottom: 5px;">File Terpilih!</div>
                <div style="font-size: 0.85rem; color: var(--color-body-mid);">${fileName}</div>
            `;
            document.getElementById('dropZone').style.borderColor = '#2e7d32';
            document.getElementById('dropZone').style.backgroundColor = '#e8f5e9';
        }
    });
</script>
<?= $this->endSection() ?>
