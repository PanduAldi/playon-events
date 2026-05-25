<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>Tambah Event Baru - Playon Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Tambah Event Baru<?= $this->endSection() ?>

<?= $this->section('page_description') ?><p>Buat event lari baru lengkap dengan tanggal pendaftaran, banner, dan kategori.</p><?= $this->endSection() ?>

<?= $this->section('page_action') ?>
    <a href="<?= base_url('admin/events') ?>" class="btn-sm">Kembali ke Event</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul style="padding-left: var(--space-lg); margin: var(--space-md) 0 0;">
                <?php foreach (session('errors') as $error): ?>
                    <?php if (is_array($error)): ?>
                        <?php foreach ($error as $err): ?>
                            <li><?= esc((string) $err) ?></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><?= esc((string) $error) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form action="<?= base_url('admin/events/create') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <h3 style="font-size: 20px; margin-bottom: var(--space-md); border-bottom: 1px solid var(--color-mute); padding-bottom: 8px;">Informasi Utama Event</h3>

            <div class="form-group">
                <label class="form-label" for="name">Nama Event</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Brebes Fun Run 2026" value="<?= old('name') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control" placeholder="Jelaskan detail event di sini..." required><?= old('description') ?></textarea>
            </div>

            <div class="grid grid-2" style="margin-bottom: var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="event_date">Tanggal & Waktu Event</label>
                    <input type="datetime-local" id="event_date" name="event_date" class="form-control" value="<?= old('event_date') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="location">Lokasi</label>
                    <input type="text" id="location" name="location" class="form-control" placeholder="Contoh: Stadion Karangbiru, Brebes" value="<?= old('location') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="maps_url">Google Maps URL</label>
                <input type="url" id="maps_url" name="maps_url" class="form-control" placeholder="https://maps.google.com/..." value="<?= old('maps_url') ?>">
            </div>

            <div class="grid grid-2" style="margin-bottom: var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="registration_open">Registrasi Dibuka</label>
                    <input type="datetime-local" id="registration_open" name="registration_open" class="form-control" value="<?= old('registration_open') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="registration_close">Registrasi Ditutup</label>
                    <input type="datetime-local" id="registration_close" name="registration_close" class="form-control" value="<?= old('registration_close') ?>" required>
                </div>
            </div>

            <div class="grid grid-3" style="margin-bottom: var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="event_type">Tipe Event</label>
                    <select id="event_type" name="event_type" class="form-control" required>
                        <option value="paid" <?= old('event_type') === 'paid' ? 'selected' : '' ?>>Berbayar (Paid)</option>
                        <option value="free" <?= old('event_type') === 'free' ? 'selected' : '' ?>>Gratis (Free)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="draft" <?= old('status') === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Aktif (Active)</option>
                        <option value="closed" <?= old('status') === 'closed' ? 'selected' : '' ?>>Tutup (Closed)</option>
                        <option value="finished" <?= old('status') === 'finished' ? 'selected' : '' ?>>Selesai (Finished)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="allow_waitlist">Waiting List</label>
                    <select id="allow_waitlist" name="allow_waitlist" class="form-control">
                        <option value="0" <?= old('allow_waitlist') == 0 ? 'selected' : '' ?>>Tidak Diizinkan</option>
                        <option value="1" <?= old('allow_waitlist') == 1 ? 'selected' : '' ?>>Izinkan</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: var(--space-xl);">
                <label class="form-label" for="banner_image">Banner Image (Maks. 2MB - Biarkan kosong jika tidak ingin diubah)</label>
                <input type="file" id="banner_image" name="banner_image" class="form-control" accept="image/*">
            </div>

            <div style="display: flex; justify-content: flex-end; align-items: center; gap: var(--space-md);">
                <a href="<?= base_url('admin/events') ?>" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan Event</button>
            </div>
        </form>
    </div>
<?= $this->endSection() ?>
