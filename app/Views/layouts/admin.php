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
            color: var(--color-ink);
        }

        .admin-shell {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        .admin-sidebar {
            width: 280px;
            background-color: var(--color-ink);
            color: var(--color-canvas-soft);
            padding: var(--space-2xl) var(--space-xl);
            display: flex;
            flex-direction: column;
            transition: transform 0.25s ease;
        }

        .sidebar-brand {
            font-family: var(--font-display);
            font-size: 24px;
            color: var(--color-primary);
            margin-bottom: var(--space-3xl);
            font-weight: 700;
        }

        .admin-sidebar nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .nav-item {
            display: block;
            padding: var(--space-sm) var(--space-md);
            color: var(--color-mute);
            border-radius: var(--radius-sm);
            font-weight: 500;
            transition: all 0.15s ease;
        }

        .nav-item:hover,
        .nav-item.active {
            background-color: var(--color-ink-soft);
            color: var(--color-on-primary);
        }

        .nav-item.active {
            border-left: 3px solid var(--color-primary);
        }

        .nav-item.nav-item-logout {
            color: #ff8d8d;
        }

        .sidebar-footer {
            margin-top: auto;
        }

        .sidebar-close {
            display: none;
            background: transparent;
            border: none;
            color: currentColor;
            font-size: 28px;
            align-self: flex-end;
            cursor: pointer;
            margin-bottom: var(--space-xl);
        }

        .admin-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--space-lg);
            padding: var(--space-lg) var(--space-xl);
            background-color: var(--color-canvas);
            border-bottom: 1px solid var(--color-canvas-soft);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .admin-header-left {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            min-width: 0;
        }

        .sidebar-toggle {
            display: none;
            border: none;
            background-color: var(--color-canvas);
            color: var(--color-ink);
            border-radius: var(--radius-pill);
            padding: 10px 14px;
            box-shadow: 0 10px 30px rgba(32, 21, 21, 0.08);
            cursor: pointer;
            font-weight: 700;
            transition: transform 0.15s ease;
        }

        .sidebar-toggle:hover {
            transform: translateY(-1px);
        }

        .page-heading h1 {
            margin: 0;
            font-size: clamp(1.9rem, 2.3vw, 2.6rem);
            line-height: 1.05;
        }

        .page-heading p {
            margin: 6px 0 0;
            color: var(--color-body-mid);
            font-size: 0.98rem;
        }

        .page-actions {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            flex-wrap: wrap;
        }

        .admin-content {
            flex: 1;
            padding: var(--space-3xl);
            overflow: auto;
        }

        .data-table-container {
            background-color: var(--color-canvas);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-mute);
            overflow-x: auto;
            margin-top: var(--space-xl);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        th,
        td {
            padding: var(--space-md) var(--space-lg);
            border-bottom: 1px solid var(--color-canvas-soft);
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: var(--color-canvas-soft);
            font-family: var(--font-display);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--color-ink-mid);
        }

        tr:hover td {
            background-color: #faf8f5;
        }

        .badge {
            padding: 4px 8px;
            border-radius: var(--radius-pill);
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-success {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        .badge-warning {
            background-color: #fff8e1;
            color: #f57f17;
        }

        .badge-danger {
            background-color: #ffebee;
            color: #c62828;
        }

        .badge-dark {
            background-color: var(--color-ink);
            color: var(--color-on-primary);
        }

        .badge-info {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .btn-sm,
        .btn-primary-sm,
        .btn-danger-sm,
        .btn-submit,
        .btn-cancel {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-body);
            font-weight: 600;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
            transition: background-color 0.15s ease, transform 0.15s ease;
            text-decoration: none;
        }

        .btn-sm,
        .btn-danger-sm {
            padding: 8px 14px;
            font-size: 0.88rem;
            background-color: var(--color-canvas-soft);
            border: 1px solid var(--color-mute);
            color: var(--color-ink);
        }

        .btn-sm:hover,
        .btn-danger-sm:hover {
            background-color: var(--color-mute);
        }

        .btn-primary-sm,
        .btn-submit {
            padding: 10px 18px;
            font-size: 0.95rem;
            background-color: var(--color-primary);
            color: var(--color-on-primary);
        }

        .btn-primary-sm:hover,
        .btn-submit:hover {
            opacity: 0.92;
        }

        .btn-danger-sm {
            color: #c62828;
            background-color: #ffebee;
        }

        .btn-danger-sm:hover {
            background-color: #ffcdd2;
        }

        .btn-cancel {
            padding: 10px 18px;
            background-color: var(--color-canvas-soft);
            border: 1px solid var(--color-mute);
            color: var(--color-ink);
        }

        .btn-cancel:hover {
            background-color: var(--color-mute);
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

        .alert {
            padding: var(--space-md);
            border-radius: var(--radius-sm);
            margin-bottom: var(--space-lg);
            border: 1px solid transparent;
        }

        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
            border-color: #ffcdd2;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-color: #c8e6c9;
        }

        .progress-track {
            height: 10px;
            overflow: hidden;
            border-radius: var(--radius-pill);
            background-color: var(--color-canvas-soft);
            border: 1px solid var(--color-mute);
        }

        .progress-bar {
            height: 100%;
            background-color: var(--color-primary);
            border-radius: var(--radius-pill);
        }

        .grid-4 {
            display: grid;
            gap: var(--space-lg);
            grid-template-columns: repeat(4, minmax(0, 1fr));
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

        .current-banner-img,
        .banner-thumb {
            width: 120px;
            height: 68px;
            object-fit: cover;
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-mute);
        }

        .banner-placeholder {
            width: 120px;
            height: 68px;
            background-color: var(--color-canvas-soft);
            border-radius: var(--radius-sm);
            border: 1px dashed var(--color-mute);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: var(--color-body-mid);
        }

        .admin-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
            z-index: 15;
        }

        .admin-shell.sidebar-open .admin-backdrop {
            opacity: 1;
            pointer-events: auto;
        }

        @media (max-width: 980px) {
            .admin-sidebar {
                position: fixed;
                left: 0;
                top: 0;
                bottom: 0;
                width: min(80vw, 320px);
                transform: translateX(-100%);
                z-index: 20;
            }

            .admin-header {
                padding-left: var(--space-lg);
                padding-right: var(--space-lg);
            }

            .sidebar-toggle,
            .sidebar-close {
                display: inline-flex;
            }

            .admin-shell.sidebar-open .admin-sidebar {
                transform: translateX(0);
            }

            .admin-content {
                padding: var(--space-xl);
            }

            table {
                min-width: 100%;
            }

            .grid-2,
            .grid-3,
            .grid-4 {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="admin-shell" id="adminShell">
        <aside class="admin-sidebar" id="adminSidebar">
            <button class="sidebar-close" id="sidebarClose" aria-label="Tutup menu">×</button>
            <div class="sidebar-brand">Playon Admin</div>
            <nav>
                <?php $segment = service('uri')->getSegment(2); ?>
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-item <?= $segment === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                <a href="<?= base_url('admin/events') ?>" class="nav-item <?= $segment === 'events' ? 'active' : '' ?>">Events</a>
                <a href="<?= base_url('admin/participants') ?>" class="nav-item <?= $segment === 'participants' ? 'active' : '' ?>">Peserta</a>
                <a href="<?= base_url('admin/scanner') ?>" class="nav-item <?= $segment === 'scanner' ? 'active' : '' ?>">Scanner</a>
                <a href="<?= base_url('admin/jerseys') ?>" class="nav-item <?= $segment === 'jerseys' ? 'active' : '' ?>">Jerseys</a>
            </nav>
            <div class="sidebar-footer">
                <a href="<?= base_url('logout') ?>" class="nav-item nav-item-logout">Logout</a>
            </div>
        </aside>

        <div class="admin-main">
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="sidebar-toggle" id="sidebarToggle" aria-controls="adminSidebar" aria-expanded="false">Menu</button>
                    <div class="page-heading">
                        <h1><?= $this->renderSection('page_title') ?></h1>
                        <?= $this->renderSection('page_description') ?>
                    </div>
                </div>

                <div class="page-actions">
                    <?= $this->renderSection('page_action') ?>
                </div>
            </header>

            <main class="admin-content">
                <?= $this->renderSection('content') ?>
            </main>
        </div>

        <div class="admin-backdrop" id="sidebarBackdrop"></div>
    </div>

    <script>
        const adminShell = document.getElementById('adminShell');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');

        const toggleSidebar = (open) => {
            const isOpen = typeof open === 'boolean' ? open : !adminShell.classList.contains('sidebar-open');
            adminShell.classList.toggle('sidebar-open', isOpen);
            sidebarToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        };

        sidebarToggle?.addEventListener('click', () => toggleSidebar(true));
        sidebarClose?.addEventListener('click', () => toggleSidebar(false));
        sidebarBackdrop?.addEventListener('click', () => toggleSidebar(false));

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && adminShell.classList.contains('sidebar-open')) {
                toggleSidebar(false);
            }
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
