<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Rapport Revenue — LibAutoEnt</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/shell.css') }}">
    <style>
        .menu-link--quiet {
            background: transparent;
            box-shadow: none;
            color: rgba(255, 255, 255, 0.82);
        }
        .menu-link--quiet:hover {
            background: rgba(252, 163, 17, 0.12);
            color: #fff;
            transform: none;
        }
        .menu-link--quiet .menu-ico {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: none;
        }
        .submenu a.is-active {
            color: #fff;
            background: rgba(252, 163, 17, 0.16);
        }
        .submenu a.is-active::before { background: var(--green-bright); }

        .page-wrap {
            flex: 1;
            padding: 0 1.5rem 1.5rem;
            min-width: 0;
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            margin-bottom: 1rem;
            justify-content: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.7rem 1.15rem;
            border-radius: 11px;
            border: none;
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            text-decoration: none;
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
            background: var(--white);
            border-radius: 18px;
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(252, 163, 17, 0.14);
            overflow: hidden;
        }

        .table-scroll { overflow-x: auto; }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 820px;
        }

        .data-table th {
            padding: 0.85rem 0.7rem;
            font-family: 'Outfit', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--muted);
            background: #f4f7fb;
            border-bottom: 1px solid rgba(13, 27, 42, 0.08);
            text-align: center;
            white-space: nowrap;
        }

        .data-table td {
            padding: 0.8rem 0.75rem;
            font-size: 0.88rem;
            border-bottom: 1px solid rgba(13, 27, 42, 0.06);
            color: var(--ink);
            vertical-align: middle;
            text-align: center;
        }

        .data-table tbody tr:hover { background: rgba(252, 163, 17, 0.06); }

        .data-table .empty {
            text-align: center;
            color: var(--muted);
            padding: 2.5rem 1rem;
        }

        .desig-cell {
            font-weight: 600;
            text-align: left !important;
            padding-left: 1rem !important;
        }
        .money { font-variant-numeric: tabular-nums; font-weight: 600; }
        .col-marge { color: #1b4332; font-weight: 700; }
        .ref-badge {
            display: inline-block;
            padding: 0.28rem 0.7rem;
            border-radius: 999px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.78rem;
            background: rgba(252, 163, 17, 0.15);
            color: var(--green-deep);
            border: 1px solid rgba(252, 163, 17, 0.3);
        }

        @media (max-width: 900px) {
            .page-wrap { padding: 0 1rem 1.25rem; }
        }

        @media print {
            .sidebar, .topbar, .toolbar, .overlay, .menu-toggle, .topbar-badge { display: none !important; }
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
        @include('partials.sidebar', ['activePage' => 'balance-vente', 'openMenu' => 'client'])

        <div class="main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div class="topbar-left">
                        <h1>Rapport Revenue</h1>
                    </div>
                </div>
                <div class="topbar-badge"><i></i> Session active</div>
            </header>

            <div class="page-wrap">
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
                        <table class="data-table" id="revenueTable">
                            <thead>
                                <tr>
                                    <th>Réf</th>
                                    <th>Désignation</th>
                                    <th>Quantité Vendue</th>
                                    <th>Pr/Achat</th>
                                    <th>Pr/Vente</th>
                                    <th>Marge</th>
                                </tr>
                            </thead>
                            <tbody id="revenueBody">
                                <tr class="empty-row">
                                    <td colspan="6" class="empty">Aucune vente — les articles vendus apparaîtront ici</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/stock-store.js') }}?v=7"></script>
    <script src="{{ asset('js/vente-store.js') }}?v=9"></script>
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

        function catalogByKey() {
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

        function getRevenueRows() {
            var map = catalogByKey();
            var agg = {};
            var bons = (window.VenteStore && VenteStore.getBons) ? VenteStore.getBons() : [];

            bons.forEach(function (bon) {
                (bon.lignes || []).forEach(function (l) {
                    var p = findProduct(map, l);
                    var key = p
                        ? ('id:' + p.id)
                        : ('ref:' + String(l.ref || l.designation || l.produit || '').toLowerCase());
                    if (!key || key === 'ref:') return;

                    if (!agg[key]) {
                        agg[key] = {
                            ref: (p && p.ref) || l.ref || '—',
                            designation: (p && p.designation) || l.designation || l.produit || '—',
                            qte: 0,
                            pa: p ? Number(p.pa) || 0 : 0,
                            pv: p ? Number(p.pv) || 0 : (Number(l.pu) || 0),
                            ca: 0
                        };
                    }
                    var qte = Number(l.qte) || 0;
                    var pu = Number(l.pu) || (p ? Number(p.pv) || 0 : 0);
                    agg[key].qte += qte;
                    agg[key].ca += qte * pu;
                    if (!p && pu > 0) agg[key].pv = pu;
                });
            });

            return Object.keys(agg).map(function (k) {
                var r = agg[k];
                var pv = r.qte > 0 ? (r.ca / r.qte) : r.pv;
                if (r.pv > 0 && r.ca === 0) pv = r.pv;
                var marge = (pv - r.pa) * r.qte;
                return {
                    ref: r.ref,
                    designation: r.designation,
                    qte: r.qte,
                    pa: r.pa,
                    pv: pv,
                    marge: marge
                };
            }).filter(function (r) {
                return r.qte > 0;
            }).sort(function (a, b) {
                return String(a.ref).localeCompare(String(b.ref), 'fr');
            });
        }

        function renderRevenue() {
            var body = document.getElementById('revenueBody');
            if (!body) return;
            var rows = getRevenueRows();
            if (!rows.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="6" class="empty">Aucune vente — les articles vendus apparaîtront ici</td></tr>';
                return;
            }
            body.innerHTML = rows.map(function (r) {
                return '' +
                    '<tr>' +
                    '<td><span class="ref-badge">' + (r.ref || '—') + '</span></td>' +
                    '<td class="desig-cell">' + (r.designation || '—') + '</td>' +
                    '<td>' + r.qte + '</td>' +
                    '<td class="money">' + money(r.pa) + '</td>' +
                    '<td class="money">' + money(r.pv) + '</td>' +
                    '<td class="money col-marge">' + money(r.marge) + '</td>' +
                    '</tr>';
            }).join('');
        }

        document.getElementById('btnImprimer').addEventListener('click', function () {
            var rows = getRevenueRows();
            var htmlRows = rows.map(function (r) {
                return '<tr>' +
                    '<td>' + (r.ref || '') + '</td>' +
                    '<td>' + (r.designation || '') + '</td>' +
                    '<td>' + r.qte + '</td>' +
                    '<td>' + money(r.pa) + '</td>' +
                    '<td>' + money(r.pv) + '</td>' +
                    '<td>' + money(r.marge) + '</td>' +
                    '</tr>';
            }).join('');

            var w = window.open('', '_blank', 'width=960,height=720');
            if (!w) {
                window.print();
                return;
            }
            w.document.write(
                '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Rapport Revenue</title>' +
                '<style>body{font-family:Arial,sans-serif;padding:24px;color:#0d1b2a}h1{font-size:18px;margin:0 0 16px}' +
                'table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:8px;text-align:center;font-size:13px}' +
                'th{background:#14213d;color:#fff}td:nth-child(2){text-align:left}</style></head><body>' +
                '<h1>Rapport Revenue</h1>' +
                '<table><thead><tr><th>Réf</th><th>Désignation</th><th>Quantité Vendue</th><th>Pr/Achat</th><th>Pr/Vente</th><th>Marge</th></tr></thead>' +
                '<tbody>' + (htmlRows || '<tr><td colspan="6">Aucune donnée</td></tr>') + '</tbody></table>' +
                '</body></html>'
            );
            w.document.close();
            w.focus();
            w.print();
        });

        renderRevenue();
        window.addEventListener('storage', renderRevenue);
        window.addEventListener('focus', renderRevenue);
    </script>
</body>
</html>
