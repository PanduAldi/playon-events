<?= $this->extend('admin/layout') ?>

<?= $this->section('title') ?>Manajemen Event - Playon Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Daftar Event<?= $this->endSection() ?>

<?= $this->section('page_description') ?><p>Kelola event lari, unggah banner, dan ubah status event dengan mudah.</p><?= $this->endSection() ?>

<?= $this->section('page_action') ?>
    <a href="<?= base_url('admin/events/new') ?>" class="btn-primary-sm">Tambah Event</a>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .banner-thumb {
        width: 80px;
        height: 45px;
        object-fit: cover;
        border-radius: var(--radius-sm);
        border: 1px solid var(--color-mute);
    }

    .banner-placeholder {
        width: 80px;
        height: 45px;
        background-color: var(--color-canvas-soft);
        border-radius: var(--radius-sm);
        border: 1px dashed var(--color-mute);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: var(--color-body-mid);
    }

    .action-row {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success">
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <div class="data-table-container">
        <table>
            <thead>
                <tr>
                    <th>Banner</th>
                    <th>Nama Event</th>
                    <th>Tanggal Event</th>
                    <th>Lokasi</th>
                    <th>Tipe</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td>
                                <?php if ($event['banner_image']): ?>
                                    <img src="<?= base_url('uploads/' . $event['banner_image']) ?>" class="banner-thumb" alt="Banner">
                                <?php else: ?>
                                    <div class="banner-placeholder">No Banner</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-weight: 700; color: var(--color-ink);"><?= esc((string) $event['name']) ?></div>
                                <div style="font-size: 12px; color: var(--color-mute);">slug: <?= esc((string) $event['slug']) ?></div>
                            </td>
                            <td><?= date('d M Y, H:i', strtotime((string) $event['event_date'])) ?></td>
                            <td><?= esc((string) $event['location']) ?></td>
                            <td>
                                <?php if ($event['event_type'] === 'free'): ?>
                                    <span class="badge badge-success">Gratis</span>
                                <?php else: ?>
                                    <span class="badge badge-info">Berbayar</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($event['status'] === 'active'): ?>
                                    <span class="badge badge-success">Aktif</span>
                                <?php elseif ($event['status'] === 'draft'): ?>
                                    <span class="badge badge-warning">Draft</span>
                                <?php elseif ($event['status'] === 'closed'): ?>
                                    <span class="badge badge-dark">Tutup</span>
                                <?php else: ?>
                                    <span class="badge badge-danger">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-row">
                                    <button type="button" class="btn-sm" data-link="<?= base_url('event/' . $event['slug'] . '/community') ?>" onclick="copyCommunityLink(this)">Copy Link</button>
                                    <a href="<?= base_url('admin/events/edit/' . $event['id']) ?>" class="btn-sm">Edit</a>
                                    <a href="<?= base_url('admin/events/delete/' . $event['id']) ?>" class="btn-danger-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--color-body-mid);">Belum ada event lari.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function copyCommunityLink(button) {
        var link = button.getAttribute('data-link');
        if (!link) return;

        function fallbackCopyText(text) {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'absolute';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, textarea.value.length);
            try {
                var successful = document.execCommand('copy');
                document.body.removeChild(textarea);
                return successful;
            } catch (err) {
                document.body.removeChild(textarea);
                return false;
            }
        }

        if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
            navigator.clipboard.writeText(link).then(function () {
                alert('Link pendaftaran komunitas disalin:\n' + link);
            }).catch(function () {
                if (fallbackCopyText(link)) {
                    alert('Link pendaftaran komunitas disalin:\n' + link);
                } else {
                    window.prompt('Salin link pendaftaran komunitas ini:', link);
                }
            });
        } else if (fallbackCopyText(link)) {
            alert('Link pendaftaran komunitas disalin:\n' + link);
        } else {
            window.prompt('Salin link pendaftaran komunitas ini:', link);
        }
    }
</script>
<?= $this->endSection() ?>
