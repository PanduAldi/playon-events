<?php /** @var array $event */ ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Playon Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        .app-shell { display: flex; min-height: 100vh; }
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
        .nav-item.active { border-left: 3px solid var(--color-primary); }
        .main-content {
            flex: 1;
            background-color: var(--color-canvas-soft);
            padding: var(--space-3xl) var(--space-2xl);
            overflow-y: auto;
        }
        
        .form-card {
            background-color: var(--color-canvas);
            padding: var(--space-2xl);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-mute);
            margin-top: var(--space-xl);
        }
        
        .form-group {
            margin-bottom: var(--space-lg);
        }
        
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-family: var(--font-display);
            color: var(--color-ink);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--color-mute);
            border-radius: var(--radius-sm);
            font-family: var(--font-body);
            font-size: 15px;
            background-color: var(--color-canvas);
            color: var(--color-ink);
        }
        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .btn-submit {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: var(--radius-sm);
            background-color: var(--color-primary);
            color: var(--color-on-primary);
            border: none;
            cursor: pointer;
            font-weight: 600;
            display: inline-block;
        }
        .btn-submit:hover { opacity: 0.9; }
        
        .btn-cancel {
            padding: 12px 24px;
            font-size: 16px;
            border-radius: var(--radius-sm);
            background-color: var(--color-canvas-soft);
            color: var(--color-ink);
            border: 1px solid var(--color-mute);
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            margin-right: var(--space-md);
        }
        .btn-cancel:hover { background-color: var(--color-mute); }
        
        .alert-danger {
            padding: var(--space-md);
            background-color: #ffebee;
            color: #c62828;
            border-radius: var(--radius-sm);
            margin-bottom: var(--space-lg);
            border: 1px solid #ffcdd2;
        }
        .alert-danger ul {
            padding-left: var(--space-lg);
        }
        
        .current-banner-box {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px;
            background-color: var(--color-canvas-soft);
            border: 1px solid var(--color-mute);
            border-radius: var(--radius-sm);
            margin-top: 8px;
        }
        .current-banner-img {
            width: 120px;
            height: 68px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-mute);
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <div class="sidebar">
            <div class="sidebar-brand">Playon Admin</div>
            <nav style="flex-grow: 1;">
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-item">Dashboard</a>
                <a href="<?= base_url('admin/events') ?>" class="nav-item active">Events</a>
                <a href="<?= base_url('admin/participants') ?>" class="nav-item">Peserta</a>
                <a href="<?= base_url('admin/scanner') ?>" class="nav-item">Scanner</a>
            </nav>
            <div style="margin-top: auto;">
                <a href="<?= base_url('logout') ?>" class="nav-item" style="color: #ef9a9a;">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <h1 style="font-size: 32px; margin-bottom: var(--space-xs);">Edit Event</h1>
            <p style="color: var(--color-body-mid);">Perbarui informasi event lari, ubah banner, atau sesuaikan tanggal pendaftaran.</p>
            
            <div class="form-card">
                <?php if (session()->has('errors')): ?>
                    <div class="alert-danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul>
                            <?php foreach (session('errors') as $error): ?>
                                <li><?= esc((string) $error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('admin/events/update/' . $event['id']) ?>" method="POST" enctype="multipart/form-data">
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

                    <div class="form-group">
                        <label class="form-label" for="quota">Kuota Maksimal Event</label>
                        <input type="number" id="quota" name="quota" class="form-control" placeholder="Contoh: 1000 (Isi 0 jika tidak dibatasi)" value="<?= old('quota', $event['quota'] ?? 0) ?>" min="0" required>
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
                    
                    <div style="display: flex; justify-content: flex-end; align-items: center;">
                        <a href="<?= base_url('admin/events') ?>" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-submit">Perbarui Event</button>
                    </div>
                </form>
            </div>

            <div class="form-card" style="margin-top: var(--space-xl);">
                <h2 style="font-size: 24px; margin-bottom: var(--space-md);">Kategori Event</h2>
                <p style="color: var(--color-body-mid); margin-bottom: var(--space-lg);">Kelola kategori pendaftaran dan batas kuota peserta (`max_participants`) untuk masing-masing kategori.</p>

                <?php if (session()->has('cat_success')): ?>
                    <div style="padding: var(--space-md); background-color: #e8f5e9; color: #2e7d32; border-radius: var(--radius-sm); margin-bottom: var(--space-md);">
                        <?= session('cat_success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('cat_error')): ?>
                    <div style="padding: var(--space-md); background-color: #ffebee; color: #c62828; border-radius: var(--radius-sm); margin-bottom: var(--space-md); border: 1px solid #ffcdd2;">
                        <?= session('cat_error') ?>
                    </div>
                <?php endif; ?>

                <table style="width: 100%; border-collapse: collapse; margin-bottom: var(--space-xl);">
                    <thead>
                        <tr style="background-color: var(--color-canvas-soft); border-bottom: 1px solid var(--color-mute);">
                            <th style="padding: 12px; text-align: left;">Nama Kategori</th>
                            <th style="padding: 12px; text-align: left;">Kode</th>
                            <th style="padding: 12px; text-align: left;">Biaya (Fee)</th>
                            <th style="padding: 12px; text-align: left;">Kuota (Max)</th>
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
                                        <td style="padding: 12px; font-weight: bold; color: var(--color-ink);">
                                            <?= $cat['registered_count'] ?> / <?= $cat['max_participants'] ?>
                                        </td>
                                        <td style="padding: 12px;">
                                            <div style="display: flex; gap: 8px;">
                                                <button type="submit" class="btn-submit" style="padding: 6px 12px; font-size: 14px;">Simpan</button>
                                                <a href="<?= base_url('admin/categories/delete/' . $cat['id'] . '?event_id=' . $event['id']) ?>" class="btn-danger-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')" style="padding: 6px 12px; display: inline-flex; align-items: center; justify-content: center; height: 33px; box-sizing: border-box;">Hapus</a>
                                            </div>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px; color: var(--color-body-mid);">Belum ada kategori untuk event ini.</td>
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
                            <label class="form-label" for="new_max">Kuota Maksimal</label>
                            <input type="number" id="new_max" name="max_participants" class="form-control" placeholder="Contoh: 200" required min="1">
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-submit" style="background-color: var(--color-ink);">Tambah Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>