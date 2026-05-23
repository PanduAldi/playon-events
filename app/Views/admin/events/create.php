<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Event Baru - Playon Admin</title>
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
        .category-row {
            background-color: var(--color-canvas-soft);
            padding: var(--space-md);
            border-radius: var(--radius-sm);
            margin-bottom: var(--space-md);
            border: 1px solid var(--color-mute);
            position: relative;
        }
        .btn-danger-sm {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
        }
        .btn-danger-sm:hover {
            background-color: #ef9a9a;
            color: white;
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
            <h1 style="font-size: 32px; margin-bottom: var(--space-xs);">Tambah Event Baru</h1>
            <p style="color: var(--color-body-mid);">Buat event lari baru lengkap dengan tanggal pendaftaran, banner image, dan kategori lari sekaligus.</p>
            
            <div class="form-card">
                <?php if (session()->has('errors')): ?>
                    <div class="alert-danger">
                        <strong>Terjadi kesalahan:</strong>
                        <ul>
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
                                <option value="0" <?= old('allow_waitlist') === '0' ? 'selected' : '' ?>>Tidak Diizinkan</option>
                                <option value="1" <?= old('allow_waitlist') === '1' ? 'selected' : '' ?>>Izinkan</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: var(--space-2xl);">
                        <label class="form-label" for="banner_image">Banner Image (Maks. 2MB)</label>
                        <input type="file" id="banner_image" name="banner_image" class="form-control" accept="image/*">
                    </div>

                    <!-- FORM DINAMIS KATEGORI -->
                    <h3 style="font-size: 20px; margin-bottom: var(--space-md); border-bottom: 1px solid var(--color-mute); padding-bottom: 8px; margin-top: var(--space-2xl);">Kategori Pendaftaran</h3>
                    <p style="color: var(--color-body-mid); margin-bottom: var(--space-lg); font-size: 14px;">Masukkan minimal satu kategori pendaftaran untuk event lari ini.</p>

                    <div id="categories-container">
                        <!-- Kategori Pertama Default -->
                        <div class="category-row">
                            <div class="grid grid-4" style="gap: 16px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Nama Kategori *</label>
                                    <input type="text" name="categories[0][name]" class="form-control" placeholder="Contoh: 10K Open" value="<?= old('categories.0.name', 'Umum') ?>" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Kode Kategori *</label>
                                    <input type="text" name="categories[0][code]" class="form-control" placeholder="Contoh: 10K" value="<?= old('categories.0.code', 'GEN') ?>" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Biaya (Fee) *</label>
                                    <input type="number" name="categories[0][fee]" class="form-control" placeholder="Contoh: 150000" value="<?= old('categories.0.fee', 0) ?>" required min="0">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label">Kuota Maksimum *</label>
                                    <input type="number" name="categories[0][max_participants]" class="form-control" placeholder="Contoh: 100" value="<?= old('categories.0.max_participants', 100) ?>" required min="1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: var(--space-2xl);">
                        <button type="button" id="btn-add-category" class="btn-cancel" style="font-size: 14px; padding: 8px 16px;">+ Tambah Baris Kategori</button>
                    </div>
                    
                    <div style="display: flex; justify-content: flex-end; align-items: center; border-top: 1px solid var(--color-mute); padding-top: var(--space-xl);">
                        <a href="<?= base_url('admin/events') ?>" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-submit">Simpan Event & Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let categoryIndex = 1;
            const container = document.getElementById('categories-container');
            const btnAdd = document.getElementById('btn-add-category');

            btnAdd.addEventListener('click', function() {
                const row = document.createElement('div');
                row.className = 'category-row';
                row.innerHTML = `
                    <div class="grid grid-4" style="gap: 16px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Nama Kategori *</label>
                            <input type="text" name="categories[\${categoryIndex}][name]" class="form-control" placeholder="Contoh: 10K Open" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Kode Kategori *</label>
                            <input type="text" name="categories[\${categoryIndex}][code]" class="form-control" placeholder="Contoh: 10K" required>
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Biaya (Fee) *</label>
                            <input type="number" name="categories[\${categoryIndex}][fee]" class="form-control" placeholder="Contoh: 150000" value="0" required min="0">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Kuota Maksimum *</label>
                            <input type="number" name="categories[\${categoryIndex}][max_participants]" class="form-control" placeholder="Contoh: 100" value="100" required min="1">
                        </div>
                    </div>
                    <div style="text-align: right; margin-top: 12px;">
                        <button type="button" class="btn-danger-sm btn-remove-category">Hapus</button>
                    </div>
                `;
                container.appendChild(row);
                categoryIndex++;
            });

            container.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-category')) {
                    e.target.closest('.category-row').remove();
                }
            });
        });
    </script>
</body>
</html>
