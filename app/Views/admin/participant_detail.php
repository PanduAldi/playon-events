<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Detail Peserta - <?= esc($registration['full_name']) ?><?= $this->endSection() ?>

<?= $this->section('page_title') ?>Detail Peserta<?= $this->endSection() ?>

<?= $this->section('page_action') ?>
    <a href="<?= base_url('admin/participants') ?>" class="btn-sm">Kembali ke Daftar</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="card-content" style="background-color: var(--color-canvas); padding: var(--space-xl);">
        <h2 style="margin-bottom: var(--space-lg);"><?= esc($registration['full_name']) ?></h2>
        <div style="display: grid; gap: var(--space-md);
                    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));">
            <div style="background: var(--color-canvas-soft); padding: var(--space-md); border-radius: var(--radius-sm);">
                <h3>Informasi Peserta</h3>
                <p><strong>Email:</strong> <?= esc($registration['email']) ?></p>
                <p><strong>Telepon:</strong> <?= esc($registration['phone']) ?></p>
                <p><strong>Tanggal Lahir:</strong> <?= esc($registration['birth_date']) ?></p>
                <p><strong>Jenis Kelamin:</strong> <?= esc($registration['gender'] === 'M' ? 'Laki-laki' : 'Perempuan') ?></p>
                <p><strong>Ukuran Kaos:</strong> <?= esc($registration['shirt_size'] ?? 'Tidak ada') ?></p>
                <p><strong>Klub:</strong> <?= esc($registration['club_name'] ?? 'Tidak ada') ?></p>
                <p><strong>Kontak Darurat:</strong> <?= esc($registration['emergency_contact']) ?></p>
                <p><strong>Catatan Medis:</strong> <?= esc($registration['medical_notes'] ?? 'Tidak ada') ?></p>
            </div>
            <div style="background: var(--color-canvas-soft); padding: var(--space-md); border-radius: var(--radius-sm);">
                <h3>Detail Registrasi</h3>
                <p><strong>BIB:</strong> <?= esc($registration['bib_number']) ?></p>
                <p><strong>Event:</strong> <?= esc($registration['event_name']) ?></p>
                <p><strong>Kategori:</strong> <?= esc($registration['category_name']) ?></p>
                <p><strong>Tipe Pendaftaran:</strong> <?= esc($registration['registration_type'] === 'community' ? 'Komunitas' : 'Pribadi') ?></p>
                <p><strong>Biaya:</strong> <?= $registration['fee'] == 0 ? 'Gratis' : 'Rp ' . number_format($registration['fee'],0,',','.') ?></p>
                <p><strong>Status Pembayaran:</strong> <?= esc(ucfirst($registration['payment_status'])) ?></p>
                <p><strong>Status Peserta:</strong> <?= esc(ucfirst($registration['status'])) ?></p>
                <p><strong>Waktu Daftar:</strong> <?= date('d M Y H:i', strtotime($registration['registered_at'])) ?></p>
                <p><strong>Check-in:</strong> <?= !empty($registration['attended_at']) ? date('d M Y H:i', strtotime($registration['attended_at'])) : 'Belum hadir' ?></p>
                <p><strong>Tipe Event:</strong> <?= esc(ucfirst($registration['event_type'])) ?></p>
                <p><strong>Lokasi:</strong> <?= esc($registration['location']) ?></p>
                <p><strong>Tanggal Event:</strong> <?= date('d M Y H:i', strtotime($registration['event_date'])) ?></p>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>
