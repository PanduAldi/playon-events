<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?><?= isset($jersey) ? 'Edit' : 'Tambah' ?> Jersey - Playon Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?><?= isset($jersey) ? 'Edit' : 'Tambah' ?> Jersey<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form action="<?= isset($jersey) ? base_url('admin/jerseys/update/'.$jersey['id']) : base_url('admin/jerseys/create') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="form-group">
                <label class="form-label">Nama Jersey</label>
                <input type="text" name="name" class="form-control" value="<?= old('name', $jersey['name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control"><?= old('description', $jersey['description'] ?? '') ?></textarea>
            </div>

            <div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-lg);">
                <div class="form-group">
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" value="<?= old('price', $jersey['price'] ?? 0) ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="active" <?= (old('status', $jersey['status'] ?? '') === 'active') ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= (old('status', $jersey['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Ukuran & Stok</label>
                <div id="size-container">
                    <?php if (isset($jersey['sizes']) && count($jersey['sizes']) > 0): ?>
                        <?php foreach ($jersey['sizes'] as $size): ?>
                            <div class="size-row" style="display: flex; gap: 12px; margin-bottom: 12px;">
                                <div style="flex: 2;">
                                    <input type="text" name="sizes[]" class="form-control" placeholder="Ukuran (S, M, L, dll)" value="<?= esc($size['size_name']) ?>">
                                </div>
                                <div style="flex: 1;">
                                    <input type="number" name="stocks[]" class="form-control" placeholder="Stok" value="<?= $size['stock'] ?>">
                                </div>
                                <div style="flex: 0.5;">
                                    <button type="button" class="btn-danger-sm btn-remove-size" style="width: 100%; padding: 10px;">×</button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="size-row" style="display: flex; gap: 12px; margin-bottom: 12px;">
                            <div style="flex: 2;">
                                <input type="text" name="sizes[]" class="form-control" placeholder="Ukuran (S, M, L, dll)">
                            </div>
                            <div style="flex: 1;">
                                <input type="number" name="stocks[]" class="form-control" placeholder="Stok">
                            </div>
                            <div style="flex: 0.5;">
                                <button type="button" class="btn-danger-sm btn-remove-size" style="width: 100%; padding: 10px;">×</button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" id="btn-add-size" class="btn-sm" style="margin-top: 8px;">+ Tambah Ukuran</button>
            </div>

            <div class="form-group">
                <label class="form-label">Foto Produk (Bisa lebih dari 1)</label>
                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                <?php if (isset($jersey['images'])): ?>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 12px;">
                        <?php foreach ($jersey['images'] as $img): ?>
                            <img src="<?= base_url('uploads/jerseys/' . $img['image_path']) ?>" class="banner-thumb" style="width: 100px; height: 100px;">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: var(--space-3xl); display: flex; gap: 12px;">
                <button type="submit" class="btn-submit"><?= isset($jersey) ? 'Simpan Perubahan' : 'Buat Jersey' ?></button>
                <a href="<?= base_url('admin/jerseys') ?>" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('size-container');
    const btnAdd = document.getElementById('btn-add-size');

    btnAdd.addEventListener('click', function() {
        const row = document.createElement('div');
        row.className = 'size-row';
        row.style.display = 'flex';
        row.style.gap = '12px';
        row.style.marginBottom = '12px';
        row.innerHTML = `
            <div style="flex: 2;">
                <input type="text" name="sizes[]" class="form-control" placeholder="Ukuran (S, M, L, dll)">
            </div>
            <div style="flex: 1;">
                <input type="number" name="stocks[]" class="form-control" placeholder="Stok">
            </div>
            <div style="flex: 0.5;">
                <button type="button" class="btn-danger-sm btn-remove-size" style="width: 100%; padding: 10px;">×</button>
            </div>
        `;
        container.appendChild(row);
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-size')) {
            e.target.closest('.size-row').remove();
        }
    });
});
</script>
<?= $this->endSection() ?>
