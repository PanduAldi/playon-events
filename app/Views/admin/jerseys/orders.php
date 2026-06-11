<?= $this->extend('layouts/admin') ?>

<?= $this->section('title') ?>Pesanan Jersey - Playon Admin<?= $this->endSection() ?>

<?= $this->section('page_title') ?>Pesanan Jersey<?= $this->endSection() ?>

<?= $this->section('page_description') ?><p>Kelola pesanan jersey dari customer dan konfirmasi pembayaran.</p><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="data-table-container">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>No. Pesanan</th>
                    <th>Jersey</th>
                    <th>Ukuran</th>
                    <th>Customer</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td style="font-size: 13px;"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td style="font-weight: 700; color: var(--color-ink);"><?= $order['order_number'] ?></td>
                        <td><?= esc($order['jersey_name']) ?> <span class="badge badge-dark">x<?= $order['quantity'] ?></span></td>
                        <td><span class="badge badge-info"><?= esc($order['size_name']) ?></span></td>
                        <td>
                            <div style="font-weight: 600;"><?= esc($order['customer_name']) ?></div>
                            <div style="font-size: 12px; color: var(--color-body-mid);"><?= esc($order['customer_phone']) ?></div>
                        </td>
                        <td>
                            <?php if ($order['payment_proof']): ?>
                                <a href="<?= base_url('uploads/payments/' . $order['payment_proof']) ?>" target="_blank" class="btn-sm" style="font-size: 11px;">Lihat Bukti</a>
                            <?php else: ?>
                                <span class="badge badge-danger">Belum Upload</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($order['status'] === 'confirmed'): ?>
                                <span class="badge badge-success">Selesai</span>
                            <?php elseif ($order['status'] === 'pending'): ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Dibatalkan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($order['status'] === 'pending'): ?>
                                <form action="<?= base_url('admin/jerseys/orders/confirm/' . $order['id']) ?>" method="POST" onsubmit="return confirm('Konfirmasi pesanan ini dan potong stok?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-primary-sm" style="padding: 6px 12px; font-size: 12px;">Konfirmasi</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--color-body-mid);">Belum ada pesanan masuk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?= $this->endSection() ?>
