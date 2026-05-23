<?= $this->extend('public/layout') ?>

<?= $this->section('title') ?>Tes Email - Playon Events<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <style>
        .email-test-page {
            padding: 48px 0;
        }
        .email-test-card {
            max-width: 760px;
            margin: 0 auto;
            background-color: var(--color-canvas);
            border: 1px solid var(--color-canvas-soft);
            border-radius: var(--radius-md);
            padding: 32px;
            box-shadow: 0 18px 50px rgba(51, 39, 32, 0.06);
        }
        .email-test-card h1 {
            margin-bottom: 16px;
        }
        .email-test-card p {
            color: var(--color-body-mid);
            margin-bottom: 24px;
        }
        .email-test-card label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--color-ink);
        }
        .email-test-card input,
        .email-test-card textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid var(--color-mute);
            border-radius: var(--radius-sm);
            font-size: 16px;
            margin-bottom: 18px;
            color: var(--color-ink);
            background-color: #fff;
        }
        .email-test-card textarea {
            min-height: 180px;
            resize: vertical;
        }
        .email-test-card .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 14px 24px;
            background-color: var(--color-primary);
            color: var(--color-on-primary);
            border: none;
            border-radius: var(--radius-pill);
            cursor: pointer;
            font-weight: 600;
        }
        .alert {
            padding: 18px 20px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 15px;
        }
        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }
        .debug-output {
            margin-top: 18px;
            padding: 16px;
            background-color: #fafafa;
            border: 1px solid var(--color-canvas-soft);
            border-radius: var(--radius-sm);
            white-space: pre-wrap;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 13px;
            color: var(--color-body-mid);
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('nav_links') ?>
    <a href="<?= base_url('/') ?>" style="font-weight: 600;">Beranda</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="email-test-page">
        <div class="container">
            <div class="email-test-card">
                <h1>Tes Kirim Email SMTP</h1>
                <p>Gunakan form ini untuk menguji konfigurasi SMTP sebelum menerapkannya di halaman checkout.</p>

                <?php if (!empty($status) && !empty($message)): ?>
                    <div class="alert <?= $status === 'success' ? 'alert-success' : 'alert-error' ?>">
                        <?= esc($message) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/email-test/send') ?>" method="post">
                    <label for="to_email">Tujuan Email</label>
                    <input type="email" id="to_email" name="to_email" placeholder="contoh@domain.com" required>

                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" placeholder="Tes Email Playon Events" value="Tes Email Playon Events">

                    <label for="message">Pesan</label>
                    <textarea id="message" name="message" placeholder="Tulis pesan tes Anda di sini...">Ini adalah percobaan pengiriman email dari Playon Events. Jika Anda menerima email ini, konfigurasi SMTP berhasil.</textarea>

                    <button type="submit" class="btn">Kirim Email Tes</button>
                </form>

                <?php if (!empty($debug)): ?>
                    <div class="debug-output">
                        <strong>Debug SMTP:</strong>
                        <br><br>
                        <?= esc($debug) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>
