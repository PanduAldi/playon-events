<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Edit Event - Playon Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Edit Event<?= $this->endSection() ?>

<?= $this->section('page_description') ?><p>Perbarui informasi event lari, banner, dan kategori dengan mudah.</p><?= $this->endSection() ?>

<?= $this->section('page_action') ?>
    <a href="<?= base_url('admin/events') ?>" class="btn-sm">Kembali ke Event</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul style="padding-left: var(--space-lg); margin: var(--space-md) 0 0;">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc((string) $error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form action="<?= base_url('admin/events/update/' . $event['id']) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label" for="name">Nama Event</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Brebes Fun Run 2026" value="<?= old('name', $event['name']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Deskripsi</label>
                <textarea id="description" name="description" class="form-control" placeholder="Jelaskan detail event di sini..." required><?= old('description', $event['description']) ?></textarea>
            </div>

            <div class="grid grid-2" style="margin-bottom: var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="event_date">Tanggal & Waktu Event</label>
                    <input type="datetime-local" id="event_date" name="event_date" class="form-control" value="<?= old('event_date', date('Y-m-d\TH:i', strtotime($event['event_date']))) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="location">Lokasi</label>
                    <input type="text" id="location" name="location" class="form-control" placeholder="Contoh: Stadion Karangbiru, Brebes" value="<?= old('location', $event['location']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="maps_url">Google Maps URL</label>
                <input type="url" id="maps_url" name="maps_url" class="form-control" placeholder="https://maps.google.com/..." value="<?= old('maps_url', $event['maps_url']) ?>">
            </div>

            <div class="grid grid-2" style="margin-bottom: var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="registration_open">Registrasi Dibuka</label>
                    <input type="datetime-local" id="registration_open" name="registration_open" class="form-control" value="<?= old('registration_open', date('Y-m-d\TH:i', strtotime($event['registration_open']))) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="registration_close">Registrasi Ditutup</label>
                    <input type="datetime-local" id="registration_close" name="registration_close" class="form-control" value="<?= old('registration_close', date('Y-m-d\TH:i', strtotime($event['registration_close']))) ?>" required>
                </div>
            </div>

            <div class="grid grid-3" style="margin-bottom: var(--space-lg);">
                <div class="form-group">
                    <label class="form-label" for="event_type">Tipe Event</label>
                    <select id="event_type" name="event_type" class="form-control" required>
                        <option value="paid" <?= old('event_type', $event['event_type']) === 'paid' ? 'selected' : '' ?>>Berbayar (Paid)</option>
                        <option value="free" <?= old('event_type', $event['event_type']) === 'free' ? 'selected' : '' ?>>Gratis (Free)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="draft" <?= old('status', $event['status']) === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="active" <?= old('status', $event['status']) === 'active' ? 'selected' : '' ?>>Aktif (Active)</option>
                        <option value="closed" <?= old('status', $event['status']) === 'closed' ? 'selected' : '' ?>>Tutup (Closed)</option>
                        <option value="finished" <?= old('status', $event['status']) === 'finished' ? 'selected' : '' ?>>Selesai (Finished)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="allow_waitlist">Waiting List</label>
                    <select id="allow_waitlist" name="allow_waitlist" class="form-control">
                        <option value="0" <?= old('allow_waitlist', $event['allow_waitlist']) == 0 ? 'selected' : '' ?>>Tidak Diizinkan</option>
                        <option value="1" <?= old('allow_waitlist', $event['allow_waitlist']) == 1 ? 'selected' : '' ?>>Izinkan</option>
                    </select>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: var(--space-xl);">
                <label class="form-label" for="banner_image">Banner Image (Maks. 2MB - Biarkan kosong jika tidak ingin diubah)</label>
                <input type="file" id="banner_image" name="banner_image" class="form-control" accept="image/*">

                <?php if ($event['banner_image']): ?>
                    <div class="current-banner-box">
                        <img src="<?= base_url('uploads/' . $event['banner_image']) ?>" class="current-banner-img" alt="Current Banner">
                        <div>
                            <div style="font-size: 14px; font-weight: 600; color: var(--color-ink);">Banner Saat Ini</div>
                            <div style="font-size: 12px; color: var(--color-body-mid);"><?= esc((string) $event['banner_image']) ?></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; justify-content: flex-end; align-items: center; gap: var(--space-md);">
                <a href="<?= base_url('admin/events') ?>" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Perbarui Event</button>
            </div>
        </form>
    </div>

    <div class="form-card" style="margin-top: var(--space-xl);">
        <h2 style="font-size: 24px; margin-bottom: var(--space-md);">Kategori Event</h2>
        <p style="color: var(--color-body-mid); margin-bottom: var(--space-lg);">Kelola kategori pendaftaran dan batas kuota peserta untuk setiap kategori.</p>

        <?php if (session()->has('cat_success')): ?>
            <div class="alert alert-success">
                <?= session('cat_success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('cat_error')): ?>
            <div class="alert alert-danger">
                <?= session('cat_error') ?>
            </div>
        <?php endif; ?>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: var(--space-xl);">
            <thead>
                <tr style="background-color: var(--color-canvas-soft); border-bottom: 1px solid var(--color-mute);">
                    <th style="padding: 12px; text-align: left;">Nama Kategori</th>
                    <th style="padding: 12px; text-align: left;">Kode</th>
                    <th style="padding: 12px; text-align: left;">Biaya (Fee)</th>
                    <th style="padding: 12px; text-align: left;">Kuota Total</th>
                    <th style="padding: 12px; text-align: left;">Kuota Pribadi</th>
                    <th style="padding: 12px; text-align: left;">Kuota Komunitas</th>
                    <th style="padding: 12px; text-align: left;">Terdaftar</th>
                    <th style="padding: 12px; text-align: left;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr style="border-bottom: 1px solid var(--color-canvas-soft);">
                            <form action="<?= base_url('admin/categories/update/' . $cat['id']) ?>" method="POST">
                                <?= csrf_field() ?>
                                <td style="padding: 12px;">
                                    <input type="text" name="name" class="form-control" value="<?= esc($cat['name']) ?>" required style="padding: 6px 10px;">
                                </td>
                                <td style="padding: 12px;">
                                    <input type="text" name="code" class="form-control" value="<?= esc($cat['code']) ?>" required style="padding: 6px 10px; width: 80px;">
                                </td>
                                <td style="padding: 12px;">
                                    <input type="number" name="fee" class="form-control" value="<?= (int)$cat['fee'] ?>" required style="padding: 6px 10px; width: 120px;">
                                </td>
                                <td style="padding: 12px;">
                                    <input type="number" name="max_participants" class="form-control" value="<?= $cat['max_participants'] ?>" required style="padding: 6px 10px; width: 100px;" min="1">
                                </td>
                                <td style="padding: 12px;">
                                    <input type="number" name="max_individual" class="form-control" value="<?= isset($cat['max_individual']) ? $cat['max_individual'] : $cat['max_participants'] ?>" required style="padding: 6px 10px; width: 100px;" min="0">
                                </td>
                                <td style="padding: 12px;">
                                    <input type="number" name="max_community" class="form-control" value="<?= isset($cat['max_community']) ? $cat['max_community'] : $cat['max_participants'] ?>" required style="padding: 6px 10px; width: 100px;" min="0">
                                </td>
                                <td style="padding: 12px; font-weight: bold; color: var(--color-ink);">
                                    <?= $cat['registered_count'] ?> / <?= $cat['max_participants'] ?>
                                </td>
                                <td style="padding: 12px;">
                                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                        <button type="submit" class="btn-submit" style="padding: 6px 12px; font-size: 14px;">Simpan</button>
                                        <a href="<?= base_url('admin/categories/delete/' . $cat['id'] . '?event_id=' . $event['id']) ?>" class="btn-danger-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" style="padding: 6px 12px; display: inline-flex; align-items: center; justify-content: center; height: 33px; box-sizing: border-box;">Hapus</a>
                                    </div>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 20px; color: var(--color-body-mid);">Belum ada kategori untuk event ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 style="font-size: 20px; margin-bottom: var(--space-md); border-top: 1px solid var(--color-mute); padding-top: var(--space-lg);">Tambah Kategori Baru</h3>
        <form action="<?= base_url('admin/categories/create') ?>" method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">

            <div class="grid grid-4" style="margin-bottom: var(--space-md); gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="new_name">Nama Kategori</label>
                    <input type="text" id="new_name" name="name" class="form-control" placeholder="Contoh: 10K Open" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="new_code">Kode Kategori</label>
                    <input type="text" id="new_code" name="code" class="form-control" placeholder="Contoh: 10K" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="new_fee">Biaya (Fee)</label>
                    <input type="number" id="new_fee" name="fee" class="form-control" placeholder="Contoh: 150000" value="0" required min="0">
                </div>
                <div class="form-group">
                    <label class="form-label" for="new_max">Kuota Total</label>
                    <input type="number" id="new_max" name="max_participants" class="form-control" placeholder="Contoh: 200" required min="1">
                </div>
                <div class="form-group">
                    <label class="form-label" for="new_max_individual">Kuota Pribadi</label>
                    <input type="number" id="new_max_individual" name="max_individual" class="form-control" placeholder="Contoh: 100" required min="0">
                </div>
                <div class="form-group">
                    <label class="form-label" for="new_max_community">Kuota Komunitas</label>
                    <input type="number" id="new_max_community" name="max_community" class="form-control" placeholder="Contoh: 100" required min="0">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-submit" style="background-color: var(--color-ink);">Tambah Kategori</button>
            </div>
        </form>
    </div>
<?= $this->endSection() ?>
