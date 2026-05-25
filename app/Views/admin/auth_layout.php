<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        body {
            min-height: 100vh;
            background-color: var(--color-canvas-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--space-xl);
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background-color: var(--color-canvas);
            border-radius: var(--radius-md);
            box-shadow: 0 24px 60px rgba(32, 21, 21, 0.12);
            padding: var(--space-3xl);
        }

        .auth-card h1 {
            margin-bottom: var(--space-md);
            font-size: 2rem;
            color: var(--color-primary);
            font-family: var(--font-display);
            letter-spacing: -0.03em;
        }

        .auth-card p {
            margin-bottom: var(--space-2xl);
            color: var(--color-body-mid);
        }

        .form-control {
            width: 100%;
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--color-mute);
            border-radius: var(--radius-sm);
            font-family: var(--font-body);
            font-size: 16px;
            margin-bottom: var(--space-lg);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
        }

        .alert {
            padding: var(--space-md);
            border-radius: var(--radius-sm);
            margin-bottom: var(--space-lg);
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef9a9a;
        }

        .btn-primary {
            width: 100%;
            padding: var(--space-md);
            border: none;
            border-radius: var(--radius-sm);
            background-color: var(--color-primary);
            color: var(--color-on-primary);
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary:hover {
            opacity: 0.95;
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <main class="auth-card">
        <?= $this->renderSection('content') ?>
    </main>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
