<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Balance des Ventes — LibAutoEnt</title>
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

        .balance-stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.85rem;
            margin-bottom: 1rem;
        }
        .stat-card {
            position: relative; overflow: hidden; border-radius: 16px;
            padding: 1rem 1.1rem 1.05rem; color: #fff; min-height: 108px;
        }
        .stat-card::before {
            content: ''; position: absolute; inset: 0;
            background:
                radial-gradient(circle at 100% 0%, rgba(255,255,255,0.18), transparent 42%),
                radial-gradient(circle at 0% 100%, rgba(0,0,0,0.1), transparent 45%);
            pointer-events: none;
        }
        .stat-card[data-tone="achats"] {
            background: linear-gradient(145deg, #1a3a5c 0%, #0d1b2a 55%, #07111c 100%);
            box-shadow: 0 8px 20px rgba(7,17,28,0.16), 0 0 0 1px rgba(100,181,246,0.2);
        }
        .stat-card[data-tone="ventes"] {
            background: linear-gradient(145deg, #fca311 0%, #e8920a 48%, #c47e00 100%);
            color: #0d1b2a;
            box-shadow: 0 8px 20px rgba(252,163,17,0.22), 0 0 0 1px rgba(252,163,17,0.3);
        }
        .stat-card[data-tone="marge"] {
            background: linear-gradient(145deg, #1b4332 0%, #0f2f24 50%, #07111c 100%);
            box-shadow: 0 8px 20px rgba(7,17,28,0.16), 0 0 0 1px rgba(77,182,172,0.24);
        }
        .stat-label {
            position: relative; z-index: 1; font-family: 'Outfit', sans-serif;
            font-size: 0.72rem; font-weight: 600; letter-spacing: 0.03em;
            text-transform: uppercase; opacity: 0.9; margin-bottom: 0.35rem;
        }
        .stat-value {
            position: relative; z-index: 1; font-family: 'Outfit', sans-serif;
            font-size: clamp(1.15rem, 2vw, 1.4rem); font-weight: 800;
            font-variant-numeric: tabular-nums; line-height: 1.2;
        }
        .stat-value small { font-size: 0.55em; font-weight: 700; margin-left: 0.15rem; opacity: 0.8; }
        .stat-hint { position: relative; z-index: 1; margin-top: 0.35rem; font-size: 0.75rem; opacity: 0.75; }

        .toolbar {
            display: flex; flex-wrap: wrap; gap: 0.65rem;
            margin-bottom: 1rem; justify-content: flex-end;
        }
        .btn {
            display: inline-flex; align-items: center; gap: 0.45rem;
            padding: 0.7rem 1.15rem; border-radius: 11px; border: none;
            font-family: 'Outfit', sans-serif; font-weight: 600; font-size: 0.9rem;
            cursor: pointer; text-decoration: none;
            transition: transform 0.15s, filter 0.15s, box-shadow 0.15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn svg { width: 16px; height: 16px; }
        .btn-print {
            color: #fff;
            background: linear-gradient(135deg, #3d7ea6, #2f6a8f);
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }
        .btn-close {
            color: #fff;
            background: linear-gradient(135deg, #5a6570, #3d4650);
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }

        .table-card {
            background: var(--white); border-radius: 18px; box-shadow: var(--shadow-card);
            border: 1px solid rgba(252,163,17,0.14); overflow: hidden;
        }
        .table-scroll { overflow-x: auto; }
        table.data-table { width: 100%; border-collapse: collapse; min-width: 860px; }
        .data-table th {
            padding: 0.85rem 0.7rem; font-family: 'Outfit', sans-serif; font-size: 0.78rem;
            font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;
            color: var(--muted); background: #f4f7fb; border-bottom: 1px solid rgba(13,27,42,0.08);
            text-align: center; white-space: nowrap;
        }
        .data-table td {
            padding: 0.8rem 0.75rem; font-size: 0.88rem;
            border-bottom: 1px solid rgba(13,27,42,0.06); color: var(--ink);
            vertical-align: middle; text-align: center;
        }
        .data-table tbody tr:hover { background: rgba(252,163,17,0.06); }
        .data-table .empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; }
        .money { font-variant-numeric: tabular-nums; font-weight: 600; }
        .col-solde { color: #c47e00; font-weight: 700; }

        @media (max-width: 900px) {
            .balance-stats { grid-template-columns: 1fr; }
            .page-wrap { padding: 0 1rem 1.25rem; }
        }
        @media print {
            .sidebar, .topbar, .toolbar, .overlay, .menu-toggle, .topbar-badge, .balance-stats { display: none !important; }
            .app { display: block !important; }
            .main { margin: 0 !important; width: 100% !important; }
            .page-wrap { padding: 0 !important; }
            .table-card { box-shadow: none; border: none; }
        }
    </style>
</head>
<body class="notranslate" translate="no">
    <div class="overlay" id="overlay"></div>

    <div class="app">
        @include('partials.sidebar', ['activePage' => 'reglement-vente', 'openMenu' => 'client'])

        <div class="main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div class="topbar-left">
                        <h1>Balance des Ventes</h1>
                    </div>
                </div>
                <div class="topbar-badge"><i></i> Session active</div>
            </header>

            <div class="page-wrap">
                <div class="balance-stats" aria-label="Indicateurs balance">
                    <article class="stat-card" data-tone="achats">
                        <div class="stat-label">Total Montant Achats</div>
                        <div class="stat-value" id="statTotalAchats">0.00 <small>DH</small></div>
                        <div class="stat-hint">Coût des ventes (P/A)</div>
                    </article>
                    <article class="stat-card" data-tone="ventes">
                        <div class="stat-label">Total Montant Ventes</div>
                        <div class="stat-value" id="statTotalVentes">0.00 <small>DH</small></div>
                        <div class="stat-hint">Somme des bons de vente</div>
                    </article>
                    <article class="stat-card" data-tone="marge">
                        <div class="stat-label">Total Marge</div>
                        <div class="stat-value" id="statTotalMarge">0.00 <small>DH</small></div>
                        <div class="stat-hint">Ventes − Achats</div>
                    </article>
                </div>

                <div class="toolbar">
                    <button type="button" class="btn btn-print" id="btnImprimer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V3h12v6"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v7H6z"/></svg>
                        Imprimer
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18" stroke-linecap="round"/></svg>
                        Fermer
                    </a>
                </div>

                <div class="table-card">
                    <div class="table-scroll">
                        <table class="data-table" id="balanceTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>N° Bn</th>
                                    <th>Montant d'Achat</th>
                                    <th>Montant de Vente</th>
                                    <th>Montant Payé</th>
                                    <th>Solde</th>
                                </tr>
                            </thead>
                            <tbody id="balanceBody">
                                <tr class="empty-row">
                                    <td colspan="6" class="empty">Aucun bon — les bons de vente apparaîtront ici</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/data-sync.js') }}?v=4"></script>
    <script src="{{ asset('js/stock-store.js') }}?v=10"></script>
    <script src="{{ asset('js/achat-store.js') }}?v=8"></script>
    <script src="{{ asset('js/vente-store.js') }}?v=10"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menuToggle');
        const sidebarClose = document.getElementById('sidebarClose');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }
        menuToggle?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
        sidebarClose?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        function money(n) {
            return (Number(n) || 0).toFixed(2) + ' DH';
        }
        function fmtMoneyHtml(n) {
            return (Number(n) || 0).toFixed(2) + ' <small>DH</small>';
        }

        function catalogMap() {
            var map = {};
            if (!window.StockStore || !StockStore.getCatalogue) return map;
            StockStore.getCatalogue().forEach(function (p) {
                if (p.id) map['id:' + p.id] = p;
                if (p.ref) map['ref:' + String(p.ref).toLowerCase()] = p;
                if (p.designation) map['desig:' + String(p.designation).toLowerCase()] = p;
            });
            return map;
        }

        function findProduct(map, ligne) {
            if (ligne.produitId && map['id:' + ligne.produitId]) return map['id:' + ligne.produitId];
            if (ligne.ref && map['ref:' + String(ligne.ref).toLowerCase()]) return map['ref:' + String(ligne.ref).toLowerCase()];
            var desig = ligne.designation || ligne.produit || '';
            if (desig && map['desig:' + String(desig).toLowerCase()]) return map['desig:' + String(desig).toLowerCase()];
            return null;
        }

        function montantAchatBon(bon, map) {
            var total = 0;
            (bon.lignes || []).forEach(function (l) {
                var p = findProduct(map, l);
                var pa = p ? (Number(p.pa) || 0) : 0;
                var qte = Number(l.qte) || 0;
                total += pa * qte;
            });
            return total;
        }

        function getBalanceRows() {
            var map = catalogMap();
            var bons = (window.VenteStore && VenteStore.getBons) ? VenteStore.getBons() : [];
            return bons.map(function (b) {
                var achat = montantAchatBon(b, map);
                var vente = Number(b.montant) || 0;
                var paye = Number(b.montantPaye) || 0;
                var solde = b.solde != null ? Number(b.solde) : Math.max(0, vente - paye);
                return {
                    date: b.date || '—',
                    numero: b.numero || '—',
                    montantAchat: achat,
                    montantVente: vente,
                    montantPaye: paye,
                    solde: solde
                };
            });
        }

        function refreshBalanceStats(rows) {
            rows = rows || getBalanceRows();
            var totalAchats = 0;
            var totalVentes = 0;
            rows.forEach(function (r) {
                totalAchats += Number(r.montantAchat) || 0;
                totalVentes += Number(r.montantVente) || 0;
            });
            document.getElementById('statTotalAchats').innerHTML = fmtMoneyHtml(totalAchats);
            document.getElementById('statTotalVentes').innerHTML = fmtMoneyHtml(totalVentes);
            document.getElementById('statTotalMarge').innerHTML = fmtMoneyHtml(totalVentes - totalAchats);
        }

        function renderBalance() {
            var body = document.getElementById('balanceBody');
            if (!body) return;
            var rows = getBalanceRows();
            refreshBalanceStats(rows);
            if (!rows.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="6" class="empty">Aucun bon — les bons de vente apparaîtront ici</td></tr>';
                return;
            }
            body.innerHTML = rows.map(function (r) {
                return '' +
                    '<tr>' +
                    '<td>' + r.date + '</td>' +
                    '<td>' + r.numero + '</td>' +
                    '<td class="money">' + money(r.montantAchat) + '</td>' +
                    '<td class="money">' + money(r.montantVente) + '</td>' +
                    '<td class="money">' + money(r.montantPaye) + '</td>' +
                    '<td class="money col-solde">' + money(r.solde) + '</td>' +
                    '</tr>';
            }).join('');
        }

        document.getElementById('btnImprimer').addEventListener('click', function () {
            var rows = getBalanceRows();
            var htmlRows = rows.map(function (r) {
                return '<tr>' +
                    '<td>' + r.date + '</td>' +
                    '<td>' + r.numero + '</td>' +
                    '<td>' + money(r.montantAchat) + '</td>' +
                    '<td>' + money(r.montantVente) + '</td>' +
                    '<td>' + money(r.montantPaye) + '</td>' +
                    '<td>' + money(r.solde) + '</td>' +
                    '</tr>';
            }).join('');

            var w = window.open('', '_blank', 'width=960,height=720');
            if (!w) {
                window.print();
                return;
            }
            w.document.write(
                '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Balance des Ventes</title>' +
                '<style>body{font-family:Arial,sans-serif;padding:24px;color:#0d1b2a}h1{font-size:18px;margin:0 0 16px}' +
                'table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:8px;text-align:center;font-size:13px}' +
                'th{background:#14213d;color:#fff}</style></head><body>' +
                '<h1>Balance des Ventes</h1>' +
                '<table><thead><tr><th>Date</th><th>N° Bn</th><th>Montant d\'Achat</th><th>Montant de Vente</th><th>Montant Payé</th><th>Solde</th></tr></thead>' +
                '<tbody>' + (htmlRows || '<tr><td colspan="6">Aucune donnée</td></tr>') + '</tbody></table>' +
                '</body></html>'
            );
            w.document.close();
            w.focus();
            w.print();
        });

        renderBalance();
        window.addEventListener('storage', renderBalance);
        window.addEventListener('focus', renderBalance);
        window.onVentesSynced = renderBalance;
        if (window.VenteStore && VenteStore.initFromServer) {
            VenteStore.initFromServer().then(renderBalance);
        }
        if (window.AchatStore && AchatStore.initFromServer) {
            AchatStore.initFromServer().then(renderBalance);
        }
    </script>
</body>
</html>
