<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <title>État Produit — 7ssabHani</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/shell.css') }}">
    <style>
        .menu-link--quiet { background: transparent; box-shadow: none; color: rgba(255,255,255,0.82); }
        .menu-link--quiet:hover { background: rgba(139,195,74,0.12); color: #fff; transform: none; }
        .menu-link--quiet .menu-ico { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.08); box-shadow: none; }
        .submenu a.is-active { color: #fff; background: rgba(139,195,74,0.16); }
        .submenu a.is-active::before { background: var(--green-bright); }

        .page-wrap { flex: 1; padding: 0 1.5rem 1.5rem; min-width: 0; }
        .toolbar { display: flex; flex-wrap: wrap; gap: 0.65rem; margin-bottom: 1rem; justify-content: flex-end; }
        .btn {
            display: inline-flex; align-items: center; gap: 0.45rem;
            padding: 0.7rem 1.15rem; border-radius: 11px; border: none;
            font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.9rem;
            cursor: pointer; text-decoration: none;
            transition: transform 0.15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn svg { width: 16px; height: 16px; }
        .btn-close { color: #fff; background: linear-gradient(135deg, #5a6570, #3d4650); box-shadow: 0 6px 14px rgba(0,0,0,0.12); }

        .table-card {
            background: var(--white); border-radius: 18px; box-shadow: var(--shadow-card);
            border: 1px solid rgba(139,195,74,0.14); overflow: hidden;
        }
        .table-scroll { overflow-x: auto; }
        table.data-table { width: 100%; border-collapse: collapse; min-width: 980px; }
        .data-table td {
            padding: 0.75rem 0.7rem; font-size: 0.88rem;
            border-bottom: 1px solid rgba(21,32,20,0.06); color: var(--ink);
            vertical-align: middle; text-align: center;
        }
        .data-table tbody tr:hover { background: rgba(139,195,74,0.06); }
        .data-table .empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; }
        .nom-cell { font-weight: 600; text-align: left !important; }

        .ref-badge {
            display: inline-block; padding: 0.25rem 0.65rem; border-radius: 999px;
            font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.75rem;
            background: rgba(139,195,74,0.15); color: var(--green-deep);
            border: 1px solid rgba(139,195,74,0.3);
        }

        .qty-input {
            width: 88px; padding: 0.4rem 0.5rem; border-radius: 8px;
            border: 1px solid rgba(21,32,20,0.12); background: #f7faf3;
            font-family: inherit; font-size: 0.88rem; text-align: center; outline: none;
        }
        .qty-input:focus {
            border-color: rgba(139,195,74,0.65);
            box-shadow: 0 0 0 3px rgba(139,195,74,0.15); background: #fff;
        }

        .etat-badge, .statut-select {
            appearance: none; -webkit-appearance: none;
            min-width: 100px; padding: 0.38rem 1.6rem 0.38rem 0.7rem;
            border-radius: 999px; border: none;
            font-family: inherit; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase; text-align: center;
        }
        .etat-badge {
            display: inline-block; padding-right: 0.7rem; cursor: default;
        }
        .etat-dispo { color: #1b5e20; background: rgba(76,175,80,0.18); box-shadow: inset 0 0 0 1px rgba(76,175,80,0.35); }
        .etat-rupture { color: #b71c1c; background: rgba(229,57,53,0.15); box-shadow: inset 0 0 0 1px rgba(229,57,53,0.35); }
        .etat-faible { color: #f57f17; background: rgba(255,193,7,0.22); box-shadow: inset 0 0 0 1px rgba(255,193,7,0.45); }

        .statut-select {
            cursor: pointer; outline: none;
            background-image:
                linear-gradient(45deg, transparent 50%, currentColor 50%),
                linear-gradient(135deg, currentColor 50%, transparent 50%);
            background-position: calc(100% - 12px) calc(50% - 2px), calc(100% - 7px) calc(50% - 2px);
            background-size: 5px 5px, 5px 5px;
            background-repeat: no-repeat;
        }
        .statut-actif { color: #1b5e20; background-color: rgba(76,175,80,0.16); box-shadow: inset 0 0 0 1px rgba(76,175,80,0.3); }
        .statut-inactif { color: #546e7a; background-color: rgba(120,144,156,0.18); box-shadow: inset 0 0 0 1px rgba(120,144,156,0.35); }

        .stock-final { font-weight: 700; }
        .stock-final.is-low { color: #f57f17; }
        .stock-final.is-out { color: #c62828; }

        @media (max-width: 900px) { .page-wrap { padding: 0 1rem 1.25rem; } }
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
                    <a href="{{ route('dashboard') }}" class="btn btn-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18" stroke-linecap="round"/></svg>
                        Fermer
                    </a>
                </div>

                <div class="table-card">
                    <div class="table-scroll">
                        <table class="data-table" id="etatTable">
                            <thead>
                                <tr>
                                    <th>Réf</th>
                                    <th>Nom Produit</th>
                                    <th>Qte Initial</th>
                                    <th>Achat</th>
                                    <th>Vente par Mois</th>
                                    <th>Stock Final</th>
                                    <th>État</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody id="etatBody">
                                <tr class="empty-row">
                                    <td colspan="8" class="empty">Aucun produit — saisissez un produit sur un bon d’achat</td>
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

        function etatBadge(p) {
            return '<span class="etat-badge etat-' + p.etat + '">' + p.etatLabel + '</span>';
        }

        function statutSelect(p) {
            return '<select class="statut-select statut-' + p.statut + '" data-key="' + p.key + '" aria-label="Statut">' +
                '<option value="actif"' + (p.statut === 'actif' ? ' selected' : '') + '>Actif</option>' +
                '<option value="inactif"' + (p.statut === 'inactif' ? ' selected' : '') + '>Inactif</option>' +
                '</select>';
        }

        function stockClass(n) {
            if (n <= 0) return ' is-out';
            if (n <= (window.StockStore ? StockStore.FAIBLE_SEUIL : 5)) return ' is-low';
            return '';
        }

        function renderEtat() {
            const body = document.getElementById('etatBody');
            if (!body || !window.StockStore) return;
            const list = StockStore.getEtatProduits();
            if (!list.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="8" class="empty">Aucun produit — saisissez un produit sur un bon d’achat</td></tr>';
                return;
            }
            body.innerHTML = list.map(function (p) {
                return '' +
                    '<tr data-key="' + p.key + '">' +
                    '<td><span class="ref-badge">' + p.ref + '</span></td>' +
                    '<td class="nom-cell">' + p.nom + '</td>' +
                    '<td><input type="number" class="qty-input" min="0" step="1" value="' + p.qteInitial + '" data-key="' + p.key + '" aria-label="Qte Initial"></td>' +
                    '<td>' + p.achat + '</td>' +
                    '<td>' + p.venteMois + '</td>' +
                    '<td class="stock-final' + stockClass(p.stockFinal) + '">' + p.stockFinal + '</td>' +
                    '<td>' + etatBadge(p) + '</td>' +
                    '<td>' + statutSelect(p) + '</td>' +
                    '</tr>';
            }).join('');

            body.querySelectorAll('.qty-input').forEach(function (inp) {
                inp.addEventListener('change', function () {
                    StockStore.setQteInitial(inp.getAttribute('data-key'), inp.value);
                    renderEtat();
                });
            });
            body.querySelectorAll('.statut-select').forEach(function (sel) {
                sel.addEventListener('change', function () {
                    StockStore.setStatut(sel.getAttribute('data-key'), sel.value);
                    renderEtat();
                });
            });
        }

        renderEtat();
        window.addEventListener('focus', renderEtat);
    </script>
</body>
</html>
