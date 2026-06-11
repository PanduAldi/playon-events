<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?></title>
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <?= $this->renderSection('styles') ?>
</head>
<body>

    <nav class="nav-bar">
        <div class="brand">Playon Brebes</div>
        <div>
            <?= $this->renderSection('nav_links') ?>
        </div>
    </nav>

    <?= $this->renderSection('content') ?>

    <footer class="footer" style="margin-top: 2%">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Playon Brebes. Didesain dengan penuh semangat.</p>
        </div>
    </footer>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
