<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Pendaftaran Komunitas - <?= esc($event['name']) ?><?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <style>
        .form-group { margin-bottom: var(--space-lg); }
        .form-label { display: block; font-weight: 600; margin-bottom: var(--space-xs); color: var(--color-ink); }
        .form-control { width: 100%; padding: var(--space-sm) var(--space-md); border: 1px solid var(--color-mute); border-radius: var(--radius-sm); font-family: var(--font-body); font-size: 16px; background-color: var(--color-canvas); color: var(--color-ink); }
        .form-control:focus { outline: none; border-color: var(--color-primary); }
        .error-text { color: #d32f2f; font-size: 14px; margin-top: 4px; }
        .alert { padding: var(--space-md); border-radius: var(--radius-sm); margin-bottom: var(--space-lg); background-color: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
        .runner-row { display: grid; gap: var(--space-md); grid-template-columns: 2fr 1fr 1fr auto; align-items: end; margin-bottom: var(--space-md); }
        .runner-row .form-group { margin-bottom: 0; }
        .runner-row .remove-runner { background: #ffebee; border: 1px solid #ffcdd2; color: #c62828; padding: 10px 14px; border-radius: var(--radius-sm); cursor: pointer; font-weight: 600; }
        .runner-row .remove-runner:hover { background: #ffcdd2; }
        .runner-header { display: flex; justify-content: space-between; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md); }
        .runner-header button { border: none; border-radius: var(--radius-sm); background-color: var(--color-primary); color: var(--color-on-primary); padding: 10px 18px; cursor: pointer; font-weight: 600; }
        .runner-header button:hover { opacity: .95; }
        .radio-group { display: flex; gap: var(--space-md); flex-wrap: wrap; }
        @media (max-width: 900px) {
            .container.grid.grid-2 {
                grid-template-columns: 1fr;
            }
            .runner-row {
                grid-template-columns: 1fr;
            }
            .runner-row .form-group {
                width: 100%;
            }
            .runner-row .remove-runner {
                width: 100%;
                margin-top: var(--space-sm);
            }
            .runner-header {
                flex-direction: column;
                align-items: stretch;
            }
            .runner-header button {
                width: 100%;
            }
            .card-content {
                padding: var(--space-xl);
            }
            .form-control {
                min-width: 0;
            }
        }
        .radio-label { display: flex; align-items: center; gap: 8px; background-color: var(--color-canvas-soft); padding: var(--space-sm) var(--space-md); border-radius: var(--radius-md); border: 1px solid var(--color-mute); cursor: pointer; }
        .radio-label:has(input:checked) { border-color: var(--color-primary); background-color: #fffaf7; }
        .hidden-field { position: absolute; left: -9999px; top: -9999px; opacity: 0; }
    </style>
<?= $this->endSection() ?>

<?= $this->section('nav_links') ?>
    <a href="<?= base_url('event/' . esc($event['slug'])) ?>" style="font-weight: 600;">Batal</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section style="padding: var(--space-4xl) 0; background-color: var(--color-canvas-soft); min-height: calc(100vh - 70px);">
        <div class="container grid grid-2">
            <div class="card-content" style="background-color: var(--color-canvas);">
                <h2 style="margin-bottom: var(--space-xl); font-size: 32px;">Pendaftaran Komunitas</h2>

                <?php if (session()->has('error')): ?>
                    <div class="alert"><?= session('error') ?></div>
                <?php endif; ?>

                <?php if (session()->has('errors')): ?>
                    <div class="alert">Terdapat kesalahan pada isian Anda. Mohon periksa kembali.</div>
                <?php endif; ?>

                <form action="<?= base_url('event/' . esc($event['slug']) . '/community') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="recaptcha_token" id="recaptcha_token" value="">
                    <div class="hidden-field">
                        <label for="honeypot">Jangan diisi</label>
                        <input type="text" id="honeypot" name="honeypot" value="">
                    </div>

                    <h3 style="margin-bottom: var(--space-md);">Data Koordinator</h3>
                    <div class="form-group">
                        <label class="form-label">Email Aktif Koordinator *</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                        <?php if(session('errors.email')): ?><div class="error-text"><?= session('errors.email') ?></div><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp Koordinator *</label>
                        <input type="text" name="phone" class="form-control" value="<?= old('phone') ?>" required placeholder="08123456789">
                        <?php if(session('errors.phone')): ?><div class="error-text"><?= session('errors.phone') ?></div><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Komunitas *</label>
                        <input type="text" name="club_name" class="form-control" value="<?= old('club_name') ?>" required>
                        <?php if(session('errors.club_name')): ?><div class="error-text"><?= session('errors.club_name') ?></div><?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori Pendaftaran *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Pilih kategori...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?> (<?= $cat['fee'] > 0 ? 'Rp ' . number_format($cat['fee'], 0, ',', '.') : 'Gratis' ?>)
                                    <?php if (!empty($cat['max_community'])): ?>
                                        - Sisa Komunitas: <?= max(0, $cat['max_community'] - ($cat['community_registered_count'] ?? 0)) ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(session('errors.category_id')): ?><div class="error-text"><?= session('errors.category_id') ?></div><?php endif; ?>
                    </div>
                    <div class="runner-header">
                        <h3>Data Pelari</h3>
                        <button type="button" id="add-runner">Tambah Pelari</button>
                    </div>
                    <div id="runner-container"></div>

                    <div style="margin-top: var(--space-xl);">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Daftar Komunitas</button>
                    </div>
                </form>
            </div>

            <div style="position: sticky; top: 100px;">
                <div class="card-content" style="background-color: var(--color-canvas);">
                    <h3 class="card-title" style="margin-bottom: var(--space-md); border-bottom: 1px solid var(--color-mute); padding-bottom: 8px;">Ringkasan Event</h3>
                    <h2 style="font-size: 24px; margin-bottom: var(--space-md);"><?= esc($event['name']) ?></h2>
                    <div style="color: var(--color-body-mid); display: flex; flex-direction: column; gap: var(--space-xs); margin-bottom: var(--space-lg); font-size: 15px;">
                        <div style="display: flex; align-items: center; gap: 8px;">Tanggal: <?= date('d M Y, H:i', strtotime($event['event_date'])) ?> WIB</div>
                        <div style="display: flex; align-items: center; gap: 8px;">Lokasi: <?= esc($event['location']) ?></div>
                        <div style="display: flex; align-items: center; gap: 8px;">Tipe: <?= $event['event_type'] == 'free' ? 'Gratis' : 'Berbayar' ?></div>
                    </div>
                    <?php if ($event['banner_image']): ?>
                        <img src="<?= base_url('uploads/' . $event['banner_image']) ?>" alt="Banner" style="width: 100%; border-radius: var(--radius-sm); border: 1px solid var(--color-mute); aspect-ratio: 16/9; object-fit: cover;">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (!empty($recaptchaSiteKey)): ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= esc($recaptchaSiteKey) ?>"></script>
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

                event.preventDefault();
                grecaptcha.ready(function () {
                    grecaptcha.execute('<?= esc($recaptchaSiteKey) ?>', { action: 'community' }).then(function (token) {
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
<script>
    var runnerContainer = document.getElementById('runner-container');
    var addRunnerButton = document.getElementById('add-runner');
    var runnerCount = 0;
    var maxRunners = 5;

    function createRunnerRow(name, gender, birthDate) {
        var row = document.createElement('div');
        row.className = 'runner-row';
        row.dataset.index = runnerCount;

        row.innerHTML = `
            <div class="form-group"><label class="form-label">Nama Pelari *</label><input type="text" name="runners[${runnerCount}][full_name]" class="form-control" value="${name || ''}" required></div>
            <div class="form-group"><label class="form-label">Jenis Kelamin *</label><select name="runners[${runnerCount}][gender]" class="form-control" required><option value="">Pilih...</option><option value="M"${gender === 'M' ? ' selected' : ''}>Laki-laki</option><option value="F"${gender === 'F' ? ' selected' : ''}>Perempuan</option></select></div>
            <div class="form-group"><label class="form-label">Tanggal Lahir</label><input type="date" name="runners[${runnerCount}][birth_date]" class="form-control" value="${birthDate || ''}"></div>
            <button type="button" class="remove-runner">Hapus</button>
        `;

        var removeButton = row.querySelector('.remove-runner');
        removeButton.addEventListener('click', function () {
            row.remove();
            updateRunnerControls();
        });

        runnerCount++;
        return row;
    }

    function updateRunnerControls() {
        var currentCount = runnerContainer.querySelectorAll('.runner-row').length;
        addRunnerButton.disabled = currentCount >= maxRunners;
        addRunnerButton.textContent = currentCount >= maxRunners ? 'Maksimal 5 Pelari' : 'Tambah Pelari';
    }

    function addRunner() {
        var currentCount = runnerContainer.querySelectorAll('.runner-row').length;
        if (currentCount >= maxRunners) {
            return;
        }
        runnerContainer.appendChild(createRunnerRow('', '', ''));
        updateRunnerControls();
    }

    addRunnerButton.addEventListener('click', addRunner);
    addRunner();
</script>
<?= $this->endSection() ?>
