<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>État Produit — LibAutoEnt</title>
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
        .toolbar {
            display: flex; flex-wrap: wrap; gap: 0.65rem; margin-bottom: 1rem;
            align-items: flex-end; justify-content: space-between;
        }
        .search-bar {
            display: flex; flex-wrap: wrap; gap: 0.65rem; align-items: flex-end; flex: 1; min-width: 0;
        }
        .search-field label {
            display: block; margin-bottom: 0.3rem; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase; color: var(--muted);
        }
        .search-field input {
            width: min(100%, 220px); padding: 0.65rem 0.85rem; border-radius: 10px;
            border: 1px solid rgba(13,27,42,0.12); background: #fff;
            font-family: inherit; font-size: 0.92rem; color: var(--ink); outline: none;
        }
        .search-field input:focus {
            border-color: rgba(252,163,17,0.65);
            box-shadow: 0 0 0 3px rgba(252,163,17,0.15);
        }
        .toolbar-actions { display: flex; flex-wrap: wrap; gap: 0.65rem; }

        .btn {
            display: inline-flex; align-items: center; gap: 0.45rem;
            padding: 0.7rem 1.15rem; border-radius: 11px; border: none;
            font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.9rem;
            cursor: pointer; text-decoration: none;
            transition: transform 0.15s, filter 0.15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn svg { width: 16px; height: 16px; }
        .btn-print {
            color: var(--ink);
            background: linear-gradient(135deg, #ffb83a, var(--green-bright) 50%, #e8920a);
            box-shadow: 0 0 18px rgba(252,163,17,0.35), 0 6px 14px rgba(0,0,0,0.12);
        }
        .btn-close { color: #fff; background: linear-gradient(135deg, #5a6570, #3d4650); box-shadow: 0 6px 14px rgba(0,0,0,0.12); }

        .table-card {
            background: var(--white); border-radius: 18px; box-shadow: var(--shadow-card);
            border: 1px solid rgba(252,163,17,0.14); overflow: hidden;
        }
        .table-scroll { overflow-x: auto; }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            min-width: 980px;
        }
        .data-table th {
            padding: 0.55rem 0.25rem; font-family: 'Outfit', sans-serif; font-size: 0.68rem;
            font-weight: 700; letter-spacing: 0.02em; text-transform: uppercase;
            color: var(--muted); background: #f4f7fb; border-bottom: 1px solid rgba(13,27,42,0.08);
            text-align: center; white-space: nowrap;
        }
        .data-table td {
            padding: 0.5rem 0.25rem; font-size: 0.8rem;
            border-bottom: 1px solid rgba(13,27,42,0.06); color: var(--ink);
            vertical-align: middle; text-align: center;
        }
        .data-table tbody tr:hover { background: rgba(252,163,17,0.06); }
        .data-table .empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; }

        .col-ref { width: 72px; }
        .col-desig { width: 120px; }
        .col-stock,
        .col-actuel { width: 58px; }
        .col-mois { width: 40px; }

        .desig-cell {
            font-weight: 600;
            text-align: left !important;
            padding-left: 0.45rem !important;
            padding-right: 0.35rem !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ref-badge {
            display: inline-block;
            max-width: 100%;
            padding: 0.15rem 0.35rem;
            border-radius: 6px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.68rem;
            line-height: 1.2;
            background: rgba(252,163,17,0.15);
            color: var(--green-deep);
            border: 1px solid rgba(252,163,17,0.3);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .qty-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 42px;
            height: 28px;
            padding: 0 0.4rem;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            font-variant-numeric: tabular-nums;
            color: var(--ink);
            background: #eef2f7;
            border: 1px solid rgba(13,27,42,0.1);
        }
        .qty-chip.is-low {
            color: #c47e00;
            background: rgba(252,163,17,0.16);
            border-color: rgba(252,163,17,0.35);
        }
        .qty-chip.is-out {
            color: #b71c1c;
            background: rgba(229,57,53,0.12);
            border-color: rgba(229,57,53,0.3);
        }

        .col-mois {
            font-variant-numeric: tabular-nums;
            font-size: 0.76rem;
            color: var(--ink-soft);
        }

        @media (max-width: 900px) {
            .page-wrap { padding: 0 1rem 1.25rem; }
            .toolbar { flex-direction: column; align-items: stretch; }
            .search-field input { width: 100%; }
            .toolbar-actions { justify-content: flex-end; }
        }

        @media print {
            .sidebar, .topbar, .toolbar, .overlay, .menu-toggle, .sidebar-show-btn { display: none !important; }
            .app { display: block; }
            .main { width: 100%; }
            .page-wrap { padding: 0; }
            .table-card { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>
<body class="notranslate" translate="no">
    <div class="overlay" id="overlay"></div>
    <div class="app">
        @include('partials.sidebar', ['activePage' => 'etat-produit', 'openMenu' => 'stock'])

        <div class="main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div class="topbar-left">
                        <h1>État Produit</h1>
                    </div>
                </div>
                <div class="topbar-badge"><i></i> Session active</div>
            </header>

            <div class="page-wrap">
                <div class="toolbar">
                    <div class="search-bar">
                        <div class="search-field">
                            <label for="searchRef">Réf</label>
                            <input type="text" id="searchRef" placeholder="Rechercher Réf…" autocomplete="off">
                        </div>
                        <div class="search-field">
                            <label for="searchDesignation">Désignation</label>
                            <input type="text" id="searchDesignation" placeholder="Rechercher Désignation…" autocomplete="off">
                        </div>
                    </div>
                    <div class="toolbar-actions">
                        <button type="button" class="btn btn-print" id="btnImprimer">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V3h12v6"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v7H6z"/></svg>
                            Imprimer
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-close">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18" stroke-linecap="round"/></svg>
                            Fermer
                        </a>
                    </div>
                </div>

                <div class="table-card">
                    <div class="table-scroll">
                        <table class="data-table" id="etatTable">
                            <thead>
                                <tr id="etatHeadRow">
                                    <th class="col-ref">Réf</th>
                                    <th class="col-desig">Désignation</th>
                                    <th class="col-stock">S. Init.</th>
                                    <th class="col-mois">Jan</th>
                                    <th class="col-mois">Fév</th>
                                    <th class="col-mois">Mar</th>
                                    <th class="col-mois">Avr</th>
                                    <th class="col-mois">Mai</th>
                                    <th class="col-mois">Juin</th>
                                    <th class="col-mois">Juil</th>
                                    <th class="col-mois">Aoû</th>
                                    <th class="col-mois">Sep</th>
                                    <th class="col-mois">Oct</th>
                                    <th class="col-mois">Nov</th>
                                    <th class="col-mois">Déc</th>
                                    <th class="col-actuel">S. Act.</th>
                                </tr>
                            </thead>
                            <tbody id="etatBody">
                                <tr class="empty-row">
                                    <td colspan="16" class="empty">Aucun produit — ajoutez des produits dans Catégorie Produit</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/data-sync.js') }}?v=3"></script>
    <script src="{{ asset('js/stock-store.js') }}?v=10"></script>
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

        const searchRef = document.getElementById('searchRef');
        const searchDesig = document.getElementById('searchDesignation');
        const COLSPAN = 16;

        function getRows() {
            if (!window.StockStore) return [];
            return StockStore.getEtatResume ? StockStore.getEtatResume() : [];
        }

        function filteredRows() {
            var qRef = (searchRef.value || '').trim().toLowerCase();
            var qDes = (searchDesig.value || '').trim().toLowerCase();
            return getRows().filter(function (p) {
                var okRef = !qRef || String(p.ref || '').toLowerCase().indexOf(qRef) !== -1;
                var okDes = !qDes || String(p.designation || '').toLowerCase().indexOf(qDes) !== -1;
                return okRef && okDes;
            });
        }

        function actuelClass(n) {
            var seuil = window.StockStore ? StockStore.FAIBLE_SEUIL : 5;
            if (n <= 0) return ' is-out';
            if (n <= seuil) return ' is-low';
            return '';
        }

        function qtyChip(n, withState) {
            var cls = 'qty-chip';
            if (withState) cls += actuelClass(n);
            return '<span class="' + cls + '">' + n + '</span>';
        }

        function renderEtat() {
            var body = document.getElementById('etatBody');
            if (!body) return;
            var list = filteredRows();
            if (!list.length) {
                var emptyMsg = getRows().length
                    ? 'Aucun résultat pour cette recherche'
                    : 'Aucun produit — ajoutez des produits dans Catégorie Produit';
                body.innerHTML = '<tr class="empty-row"><td colspan="' + COLSPAN + '" class="empty">' + emptyMsg + '</td></tr>';
                return;
            }
            body.innerHTML = list.map(function (p) {
                var mois = p.ventesParMois || [0,0,0,0,0,0,0,0,0,0,0,0];
                var moisCells = '';
                for (var i = 0; i < 12; i++) {
                    var v = Number(mois[i]) || 0;
                    moisCells += '<td class="col-mois">' + (v || '—') + '</td>';
                }
                var init = Number(p.stockInitial) || 0;
                var actuel = Number(p.qteActuel);
                if (isNaN(actuel)) actuel = 0;
                var ref = p.ref || '—';
                var desig = p.designation || '';
                return '' +
                    '<tr>' +
                    '<td class="col-ref"><span class="ref-badge" title="' + ref.replace(/"/g, '&quot;') + '">' + ref + '</span></td>' +
                    '<td class="desig-cell col-desig" title="' + desig.replace(/"/g, '&quot;') + '">' + desig + '</td>' +
                    '<td class="col-stock">' + qtyChip(init, false) + '</td>' +
                    moisCells +
                    '<td class="col-actuel">' + qtyChip(actuel, true) + '</td>' +
                    '</tr>';
            }).join('');
        }

        searchRef.addEventListener('input', renderEtat);
        searchDesig.addEventListener('input', renderEtat);

        document.getElementById('btnImprimer').addEventListener('click', function () {
            window.print();
        });

        var bootEtat = (window.StockStore && StockStore.initCatalogFromServer)
            ? StockStore.initCatalogFromServer()
            : Promise.resolve();
        bootEtat.then(renderEtat);
        window.addEventListener('focus', function () {
            if (window.StockStore && StockStore.initCatalogFromServer) {
                StockStore.initCatalogFromServer().then(renderEtat);
            } else {
                renderEtat();
            }
        });
        window.onCatalogueSynced = renderEtat;
    </script>
</body>
</html>
