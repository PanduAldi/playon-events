<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Checkout - <?= esc($event['name']) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <style>
        .form-group {
            margin-bottom: var(--space-lg);
        }
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: var(--space-xs);
            color: var(--color-ink);
        }
        .form-control {
            width: 100%;
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--color-mute);
            border-radius: var(--radius-sm);
            font-family: var(--font-body);
            font-size: 16px;
            background-color: var(--color-canvas);
            color: var(--color-ink);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
        }
        .error-text {
            color: #d32f2f;
            font-size: 14px;
            margin-top: 4px;
        }
        .alert {
            padding: var(--space-md);
            border-radius: var(--radius-sm);
            margin-bottom: var(--space-lg);
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
        .radio-group {
            display: flex;
            gap: var(--space-md);
            flex-wrap: wrap;
        }
        .radio-label {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: var(--color-canvas-soft);
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-mute);
            cursor: pointer;
        }
        .radio-label:has(input:checked) {
            border-color: var(--color-primary);
            background-color: #fffaf7; /* very light orange */
        }
        .event-banner {
            width: 100%;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-mute);
            object-fit: cover;
            display: block;
        }
        .event-banner.landscape {
            aspect-ratio: 16 / 9;
        }
        .event-banner.portrait {
            aspect-ratio: 9 / 16;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('nav_links') ?>
    <a href="<?= base_url('event/' . esc($event['slug'])) ?>" style="font-weight: 600;">Batal</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section style="padding: var(--space-4xl) 0; background-color: var(--color-canvas-soft); min-height: calc(100vh - 70px);">
        <div class="container grid grid-2">
            
            <div class="card-content" style="background-color: var(--color-canvas);">
                <h2 style="margin-bottom: var(--space-xl); font-size: 32px;">Form Pendaftaran</h2>
                
                <?php if (session()->has('error')): ?>
                    <div class="alert"><?= session('error') ?></div>
                <?php endif; ?>

                <?php if (session()->has('errors')): ?>
                    <div class="alert">
                        Terdapat kesalahan pada isian Anda. Mohon periksa kembali.
                    </div>
                <?php endif; ?>

                <?php $errors = session('errors') ?? []; ?>
                <?php if (!empty($categories)): ?>
                    <form action="<?= base_url('event/' . esc($event['slug']) . '/checkout') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="recaptcha_token" id="recaptcha_token" value="">
                        
                        <div class="form-group">
                            <label class="form-label">Pilih Kategori *</label>
                            <div class="radio-group">
                                <?php foreach($categories as $cat): ?>
                                    <label class="radio-label">
                                        <input type="radio" name="category_id" value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'checked' : '' ?> required>
                                        <span>
                                            <?= esc($cat['name']) ?> (<?= $cat['fee'] > 0 ? 'Rp ' . number_format($cat['fee'], 0, ',', '.') : 'Gratis' ?>)
                                            <?php if (!empty($cat['max_individual'])): ?>
                                                <br><small style="color: var(--color-body-mid);">Sisa Kuota Pribadi: <?= max(0, $cat['max_individual'] - ($cat['individual_registered_count'] ?? 0)) ?></small>
                                            <?php endif; ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <?php if (!empty($errors['category_id'])): ?><div class="error-text"><?= esc($errors['category_id']) ?></div><?php endif; ?>
                        </div>

                        <div style="display:none;">
                            <label for="honeypot">Tidak diisi jika Anda bukan robot</label>
                            <input type="text" id="honeypot" name="honeypot" value="">
                        </div>

                        <h3 style="margin: var(--space-2xl) 0 var(--space-md); font-size: 20px;">Data Diri</h3>

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap Sesuai KTP *</label>
                            <input type="text" name="full_name" class="form-control" value="<?= old('full_name') ?>" required>
                            <?php if (!empty($errors['full_name'])): ?><div class="error-text"><?= esc($errors['full_name']) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Aktif *</label>
                            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                            <?php if (!empty($errors['email'])): ?><div class="error-text"><?= esc($errors['email']) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp *</label>
                            <input type="tel" name="phone" class="form-control" value="<?= old('phone') ?>" required placeholder="08123456789" pattern="[0-9]{9,15}">
                            <?php if (!empty($errors['phone'])): ?><div class="error-text"><?= esc($errors['phone']) ?></div><?php endif; ?>
                        </div>

                        <div style="display: flex; gap: var(--space-md);">
                            <div class="form-group" style="flex: 1;">
                                <label class="form-label">Tanggal Lahir *</label>
                                <input type="date" name="birth_date" class="form-control" value="<?= old('birth_date') ?>" required>
                            </div>
                            <div class="form-group" style="flex: 1;">
                                <label class="form-label">Jenis Kelamin *</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">Pilih...</option>
                                    <option value="M" <?= old('gender') == 'M' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="F" <?= old('gender') == 'F' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="display: none;">
                            <label class="form-label">Ukuran Kaos</label>
                            <select name="shirt_size" class="form-control">
                                <option value="">Tidak perlu kaos / Pilih...</option>
                                <option value="XS">XS</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Club Lari / Komunitas (Opsional)</label>
                            <input type="text" name="club_name" class="form-control" value="<?= old('club_name') ?>" placeholder="Contoh: Brebes Runner">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kontak Darurat (Nama - Hubungan - Nomor HP) *</label>
                            <input type="text" name="emergency_contact" class="form-control" value="<?= old('emergency_contact') ?>" required placeholder="Contoh: Budi (Ayah) - 08123456789">
                            <?php if (!empty($errors['emergency_contact'])): ?><div class="error-text"><?= esc($errors['emergency_contact']) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Riwayat Penyakit / Catatan Medis (Opsional)</label>
                            <textarea name="medical_notes" class="form-control" placeholder="Contoh: Asthma, Alergi obat pencahar, dll."><?= old('medical_notes') ?></textarea>
                        </div>

                        <div style="margin-top: var(--space-xl);">
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar & Lanjut</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert" style="background-color: #e8f5e9; color: #256029; border-color: #c8e6c9;">
                        <strong>Kuota Penuh.</strong> Semua kategori untuk event ini sudah penuh.
                        Silakan pilih event lain atau kembali lagi nanti.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Ringkasan Event -->
            <div style="position: sticky; top: 100px;">
                <div class="card-content" style="background-color: var(--color-canvas);">
                    <h3 class="card-title" style="margin-bottom: var(--space-md); border-bottom: 1px solid var(--color-mute); padding-bottom: 8px;">Ringkasan Event</h3>
                    
                    <h2 style="font-size: 24px; margin-bottom: var(--space-md);"><?= esc($event['name']) ?></h2>
                    
                    <div style="color: var(--color-body-mid); display: flex; flex-direction: column; gap: var(--space-xs); margin-bottom: var(--space-lg); font-size: 15px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                            <?= date('d M Y, H:i', strtotime($event['event_date'])) ?> WIB
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <?= esc($event['location']) ?>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            Tipe: <?= $event['event_type'] == 'free' ? 'Gratis' : 'Berbayar' ?>
                        </div>
                    </div>

                    <?php if ($event['banner_image']): ?>
                        <img id="eventBanner" class="event-banner" src="<?= base_url('uploads/' . $event['banner_image']) ?>" alt="Banner">
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </section>

    <?=  $this->endSection('content') ?> 

    <?= $this->section('scripts') ?>
    <?php if (!empty($recaptchaSiteKey)): ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?= esc($recaptchaSiteKey) ?>"></script>
        <script>

            function updateBannerOrientation() {
                var banner = document.getElementById('eventBanner');
                if (!banner) {
                    return;
                }

                function applyOrientation() {
                    if (banner.naturalWidth && banner.naturalHeight) {
                        banner.classList.remove('portrait', 'landscape');
                        if (banner.naturalWidth >= banner.naturalHeight) {
                            banner.classList.add('landscape');
                        } else {
                            banner.classList.add('portrait');
                        }
                    }
                }

                if (banner.complete) {
                    applyOrientation();
                } else {
                    banner.addEventListener('load', applyOrientation);
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                updateBannerOrientation();
                var form = document.querySelector('form');
                if (!form) return;

                var isSubmitting = false;
                var submitButton = form.querySelector('button[type="submit"]');

                form.addEventListener('submit', function (event) {
                    if (isSubmitting) {
                        event.preventDefault();
                        return;
                    }

                    isSubmitting = true;
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.textContent = 'Mengirim...';
                    }

                    event.preventDefault();
                    grecaptcha.ready(function () {
                        grecaptcha.execute('<?= esc($recaptchaSiteKey) ?>', { action: 'checkout' }).then(function (token) {
                            var input = document.getElementById('recaptcha_token');
                            if (input) {
                                input.value = token;
                            }
                            form.submit();
                        });
                    });
                });
            });
        </script>
    <?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.querySelector('form');
            if (!form) return;

            var isSubmitting = false;
            var submitButton = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', function (event) {
                if (isSubmitting) {
                    event.preventDefault();
                    return;
                }

                isSubmitting = true;
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'Mengirim...';
                }
            });
        });
    </script>
<?= $this->endSection() ?>
