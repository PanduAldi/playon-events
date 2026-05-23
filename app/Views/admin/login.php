<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Playon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
        body {
            background-color: var(--color-canvas-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .login-card {
            background-color: var(--color-canvas);
            padding: var(--space-3xl);
            border-radius: var(--radius-md);
            box-shadow: 0 4px 6px rgba(32, 21, 21, 0.05);
            width: 100%;
            max-width: 400px;
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
    </style>
</head>
<body>

    <div class="login-card">
        <div style="text-align: center; margin-bottom: var(--space-2xl);">
            <h1 style="color: var(--color-primary); font-family: var(--font-display); font-size: 32px; letter-spacing: -1px;">Playon Admin</h1>
            <p style="color: var(--color-body-mid); font-size: 16px;">Masuk ke panel manajemen event</p>
        </div>

        <?php if (session()->has('error')): ?>
            <div class="alert"><?= session('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>
            <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password admin" required>
            
            <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk Dashboard</button>
        </form>
    </div>

</body>
</html>
