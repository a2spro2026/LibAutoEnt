<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Catégorie Produit — LibAutoEnt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/shell.css') }}">
    <style>
        .menu-link--quiet { background: transparent; box-shadow: none; color: rgba(255,255,255,0.82); }
        .menu-link--quiet:hover { background: rgba(252,163,17,0.12); color: #fff; transform: none; }
        .menu-link--quiet .menu-ico { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.08); box-shadow: none; }
        .submenu a.is-active { color: #fff; background: rgba(252,163,17,0.16); }
        .submenu a.is-active::before { background: var(--green-bright); }

        .page-wrap { flex: 1; padding: 0 1.5rem 1.5rem; min-width: 0; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 0.65rem; margin-bottom: 1rem; justify-content: flex-end; }
        .btn {
            display: inline-flex; align-items: center; gap: 0.45rem;
            padding: 0.7rem 1.15rem; border-radius: 11px; border: none;
            font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.9rem;
            cursor: pointer; text-decoration: none;
            transition: transform 0.15s, filter 0.15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn svg { width: 16px; height: 16px; }
        .btn-close { color: #fff; background: linear-gradient(135deg, #5a6570, #3d4650); box-shadow: 0 6px 14px rgba(0,0,0,0.12); }

        .table-card {
            background: var(--white); border-radius: 18px; box-shadow: var(--shadow-card);
            border: 1px solid rgba(252,163,17,0.14); overflow: hidden;
        }
        .table-scroll { overflow-x: auto; }
        table.data-table { width: 100%; border-collapse: collapse; min-width: 480px; }
        .data-table td {
            padding: 0.85rem 0.9rem; font-size: 0.9rem;
            border-bottom: 1px solid rgba(21,32,20,0.06); color: var(--ink);
            vertical-align: middle; text-align: center;
        }
        .data-table tbody tr:hover { background: rgba(252,163,17,0.06); }
        .data-table .empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; }
        .ref-badge {
            display: inline-block; padding: 0.28rem 0.7rem; border-radius: 999px;
            font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.78rem;
            background: rgba(252,163,17,0.15); color: var(--green-deep);
            border: 1px solid rgba(252,163,17,0.3);
        }
        .nom-cell { font-weight: 600; text-align: left !important; padding-left: 1.25rem !important; }

        @media (max-width: 900px) { .page-wrap { padding: 0 1rem 1.25rem; } }
    </style>
</head>
<body class="notranslate" translate="no">
    <div class="overlay" id="overlay"></div>
    <div class="app">
        @include('partials.sidebar', ['activePage' => 'categorie-produit', 'openMenu' => 'stock'])

        <div class="main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div class="topbar-left">
                        <h1>Catégorie Produit</h1>
                    </div>
                </div>
                <div class="topbar-badge"><i></i> Session active</div>
            </header>

            <div class="page-wrap">
                <div class="toolbar">
                    <a href="{{ route('dashboard') }}" class="btn btn-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18" stroke-linecap="round"/></svg>
                        Fermer
                    </a>
                </div>

                <div class="table-card">
                    <div class="table-scroll">
                        <table class="data-table" id="catTable">
                            <thead>
                                <tr>
                                    <th>Réf</th>
                                    <th>Nom</th>
                                </tr>
                            </thead>
                            <tbody id="catBody">
                                <tr class="empty-row">
                                    <td colspan="2" class="empty">Aucune catégorie — saisissez une catégorie sur un bon d’achat</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/stock-store.js') }}?v=2"></script>
    <script>

        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menuToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        function openSidebar() { sidebar.classList.add('open'); overlay.classList.add('show'); document.body.style.overflow = 'hidden'; }
        function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('show'); document.body.style.overflow = ''; }
        menuToggle?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
        sidebarClose?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        function renderCategories() {
            const body = document.getElementById('catBody');
            if (!body || !window.StockStore) return;
            const list = StockStore.getCategories();
            if (!list.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="2" class="empty">Aucune catégorie — saisissez une catégorie sur un bon d’achat</td></tr>';
                return;
            }
            body.innerHTML = list.map(function (p) {
                return '<tr><td><span class="ref-badge">' + (p.ref || '') + '</span></td>' +
                    '<td class="nom-cell">' + (p.nom || '') + '</td></tr>';
            }).join('');
        }

        renderCategories();
    </script>
</body>
</html>
