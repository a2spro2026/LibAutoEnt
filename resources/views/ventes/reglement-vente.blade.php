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
    <link rel="stylesheet" href="{{ asset('css/shell.css') }}?v=2">
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
            margin-bottom: 1rem; align-items: flex-end; justify-content: space-between;
        }
        .search-bar {
            display: flex; flex-wrap: wrap; gap: 0.65rem; align-items: flex-end; flex: 1; min-width: 0;
        }
        .search-field label {
            display: block; margin-bottom: 0.3rem; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase; color: var(--muted);
        }
        .search-field input {
            width: min(100%, 180px); padding: 0.65rem 0.85rem; border-radius: 10px;
            border: 1px solid rgba(13,27,42,0.12); background: #fff;
            font-family: inherit; font-size: 0.92rem; color: var(--ink); outline: none;
        }
        .search-field input:focus {
            border-color: rgba(252,163,17,0.65);
            box-shadow: 0 0 0 3px rgba(252,163,17,0.15);
        }
        .search-field .btn { margin-top: 0; }
        .toolbar-actions { display: flex; flex-wrap: wrap; gap: 0.65rem; }
        .period-hint {
            margin: -0.35rem 0 0.85rem; font-size: 0.85rem; color: var(--muted); font-weight: 500;
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
        .btn-reset {
            color: #0d1b2a;
            background: #f4f7fb;
            border: 1px solid rgba(13,27,42,0.12);
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        }
        .btn-reset:hover { background: #eef2f8; }

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
            .toolbar { flex-direction: column; align-items: stretch; }
            .search-field input { width: 100%; }
            .toolbar-actions { justify-content: flex-end; }
        }
        @media print {
            .sidebar, .topbar, .toolbar, .overlay, .menu-toggle, .topbar-badge, .balance-stats, .period-hint { display: none !important; }
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
                    <div class="search-bar">
                        <div class="search-field">
                            <label for="filterMoisDe">Mois — De</label>
                            <input type="month" id="filterMoisDe" autocomplete="off">
                        </div>
                        <div class="search-field">
                            <label for="filterMoisA">À</label>
                            <input type="month" id="filterMoisA" autocomplete="off">
                        </div>
                        <div class="search-field">
                            <label aria-hidden="true">&nbsp;</label>
                            <button type="button" class="btn btn-reset" id="btnReset" title="Réinitialiser les filtres">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 15-6.7L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-15 6.7L3 16"/><path d="M3 21v-5h5"/></svg>
                                Reset
                            </button>
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
                <p class="period-hint" id="periodHint" hidden></p>

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

    <script src="{{ asset('js/data-sync.js') }}?v=5"></script>
    <script src="{{ asset('js/stock-store.js') }}?v=10"></script>
    <script src="{{ asset('js/achat-store.js') }}?v=8"></script>
    <script src="{{ asset('js/vente-store.js') }}?v=12"></script>
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

        const COMPANY_NAME = @json(config('app.name', 'LibAutoEnt'));
        const filterMoisDe = document.getElementById('filterMoisDe');
        const filterMoisA = document.getElementById('filterMoisA');
        const periodHint = document.getElementById('periodHint');

        function money(n) {
            return (Number(n) || 0).toFixed(2) + ' DH';
        }
        function fmtMoneyHtml(n) {
            return (Number(n) || 0).toFixed(2) + ' <small>DH</small>';
        }
        function escapeHtml(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function bonMonthKey(dateStr) {
            if (!dateStr || typeof dateStr !== 'string') return '';
            if (dateStr.indexOf('-') !== -1 && dateStr.length >= 7) {
                return dateStr.slice(0, 7);
            }
            var p = dateStr.split('/');
            if (p.length !== 3) return '';
            var mm = String(p[1] || '').padStart(2, '0');
            var yyyy = String(p[2] || '');
            if (!yyyy || mm === '00') return '';
            return yyyy + '-' + mm;
        }

        function getMonthRange() {
            var from = (filterMoisDe && filterMoisDe.value) || '';
            var to = (filterMoisA && filterMoisA.value) || '';
            if (from && to && from > to) {
                var tmp = from;
                from = to;
                to = tmp;
            }
            return { from: from, to: to };
        }

        function formatMonthLabel(ym) {
            if (!ym || ym.length < 7) return '';
            var parts = ym.split('-');
            var months = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
            var mi = parseInt(parts[1], 10) - 1;
            if (mi < 0 || mi > 11) return ym;
            return months[mi] + ' ' + parts[0];
        }

        function periodLabel() {
            var range = getMonthRange();
            if (!range.from && !range.to) return '';
            if (range.from && range.to) {
                if (range.from === range.to) return 'Période : ' + formatMonthLabel(range.from);
                return 'Période : De ' + formatMonthLabel(range.from) + ' à ' + formatMonthLabel(range.to);
            }
            if (range.from) return 'Période : À partir de ' + formatMonthLabel(range.from);
            return 'Période : Jusqu’à ' + formatMonthLabel(range.to);
        }

        function periodPrintLabel() {
            var range = getMonthRange();
            if (!range.from && !range.to) return 'Toutes périodes';
            if (range.from && range.to) {
                if (range.from === range.to) return formatMonthLabel(range.from);
                return 'Du ' + formatMonthLabel(range.from) + ' au ' + formatMonthLabel(range.to);
            }
            if (range.from) return 'À partir de ' + formatMonthLabel(range.from);
            return 'Jusqu’à ' + formatMonthLabel(range.to);
        }

        function bonInMonthRange(bon, range) {
            if (!range.from && !range.to) return true;
            var key = bonMonthKey(bon && bon.date);
            if (!key) return false;
            if (range.from && key < range.from) return false;
            if (range.to && key > range.to) return false;
            return true;
        }

        function balanceEtatNumber() {
            var d = new Date();
            var y = d.getFullYear();
            var m = String(d.getMonth() + 1).padStart(2, '0');
            var day = String(d.getDate()).padStart(2, '0');
            var h = String(d.getHours()).padStart(2, '0');
            var min = String(d.getMinutes()).padStart(2, '0');
            return 'BAL-V-' + y + m + day + '-' + h + min;
        }

        function formatPrintDate() {
            var d = new Date();
            var dd = String(d.getDate()).padStart(2, '0');
            var mm = String(d.getMonth() + 1).padStart(2, '0');
            return dd + '/' + mm + '/' + d.getFullYear();
        }

        function computeTotals(rows) {
            var totals = { achats: 0, ventes: 0, paye: 0, solde: 0 };
            (rows || []).forEach(function (r) {
                totals.achats += Number(r.montantAchat) || 0;
                totals.ventes += Number(r.montantVente) || 0;
                totals.paye += Number(r.montantPaye) || 0;
                totals.solde += Number(r.solde) || 0;
            });
            totals.marge = totals.ventes - totals.achats;
            return totals;
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
            var range = getMonthRange();
            var bons = (window.VenteStore && VenteStore.getBons) ? VenteStore.getBons() : [];
            return bons.filter(function (b) {
                return bonInMonthRange(b, range);
            }).map(function (b) {
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

        function updatePeriodHint() {
            if (!periodHint) return;
            var label = periodLabel();
            if (label) {
                periodHint.hidden = false;
                periodHint.textContent = label;
            } else {
                periodHint.hidden = true;
                periodHint.textContent = '';
            }
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
            updatePeriodHint();
            var rows = getBalanceRows();
            refreshBalanceStats(rows);
            var filtered = !!(getMonthRange().from || getMonthRange().to);
            if (!rows.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="6" class="empty">' +
                    (filtered
                        ? 'Aucun bon sur cette période'
                        : 'Aucun bon — les bons de vente apparaîtront ici') +
                    '</td></tr>';
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

        filterMoisDe.addEventListener('change', renderBalance);
        filterMoisA.addEventListener('change', renderBalance);

        document.getElementById('btnReset').addEventListener('click', function () {
            if (filterMoisDe) filterMoisDe.value = '';
            if (filterMoisA) filterMoisA.value = '';
            renderBalance();
        });

        document.getElementById('btnImprimer').addEventListener('click', function () {
            var rows = getBalanceRows();
            var totals = computeTotals(rows);
            var etatNum = balanceEtatNumber();
            var periode = periodPrintLabel();
            var printDate = formatPrintDate();
            var range = getMonthRange();

            var htmlRows = rows.map(function (r, i) {
                var zebra = i % 2 ? ' class="alt"' : '';
                return '<tr' + zebra + '>' +
                    '<td>' + escapeHtml(r.date) + '</td>' +
                    '<td>' + escapeHtml(r.numero) + '</td>' +
                    '<td class="num">' + escapeHtml(money(r.montantAchat)) + '</td>' +
                    '<td class="num">' + escapeHtml(money(r.montantVente)) + '</td>' +
                    '<td class="num">' + escapeHtml(money(r.montantPaye)) + '</td>' +
                    '<td class="num solde">' + escapeHtml(money(r.solde)) + '</td>' +
                    '</tr>';
            }).join('');

            var totalsRow =
                '<tr class="totals-row">' +
                '<td colspan="2"><strong>TOTAUX</strong></td>' +
                '<td class="num"><strong>' + escapeHtml(money(totals.achats)) + '</strong></td>' +
                '<td class="num highlight"><strong>' + escapeHtml(money(totals.ventes)) + '</strong></td>' +
                '<td class="num"><strong>' + escapeHtml(money(totals.paye)) + '</strong></td>' +
                '<td class="num solde"><strong>' + escapeHtml(money(totals.solde)) + '</strong></td>' +
                '</tr>';

            var summaryCards =
                '<div class="summary-grid">' +
                '<div class="summary-card"><span>Total Achats</span><strong>' + escapeHtml(money(totals.achats)) + '</strong></div>' +
                '<div class="summary-card accent"><span>Total Ventes</span><strong>' + escapeHtml(money(totals.ventes)) + '</strong></div>' +
                '<div class="summary-card"><span>Total Marge</span><strong>' + escapeHtml(money(totals.marge)) + '</strong></div>' +
                '<div class="summary-card"><span>Total Payé</span><strong>' + escapeHtml(money(totals.paye)) + '</strong></div>' +
                '</div>';

            var printCss =
                '*{box-sizing:border-box;margin:0;padding:0}' +
                'body{font-family:"Segoe UI",Arial,sans-serif;color:#0d1b2a;background:#f8fafc;padding:28px}' +
                '.sheet{max-width:980px;margin:0 auto;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 12px 40px rgba(13,27,42,0.12)}' +
                '.head{background:linear-gradient(135deg,#0d1b2a 0%,#14213d 55%,#1b4332 100%);color:#fff;padding:28px 32px 24px;position:relative}' +
                '.head::after{content:"";position:absolute;bottom:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#fca311,#ffd166,#fca311)}' +
                '.company{font-size:26px;font-weight:800;letter-spacing:0.02em;margin-bottom:4px}' +
                '.doc-title{font-size:15px;font-weight:600;opacity:0.92;text-transform:uppercase;letter-spacing:0.12em;color:#fca311}' +
                '.meta{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;padding:18px 32px;background:#f4f7fb;border-bottom:1px solid rgba(13,27,42,0.08)}' +
                '.meta-item label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#5a6570;margin-bottom:4px}' +
                '.meta-item span{font-size:13px;font-weight:600;color:#0d1b2a}' +
                '.content{padding:24px 32px 32px}' +
                '.summary-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:22px}' +
                '.summary-card{background:#f8fafc;border:1px solid rgba(13,27,42,0.08);border-radius:10px;padding:12px 14px;text-align:center}' +
                '.summary-card span{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#5a6570;margin-bottom:6px}' +
                '.summary-card strong{font-size:15px;font-weight:800;color:#0d1b2a}' +
                '.summary-card.accent{background:linear-gradient(145deg,#fff8eb,#fff3d6);border-color:rgba(252,163,17,0.35)}' +
                '.summary-card.accent strong{color:#c47e00}' +
                'table{width:100%;border-collapse:collapse;font-size:12px}' +
                'th{background:#14213d;color:#fff;padding:10px 8px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.05em}' +
                'td{padding:9px 8px;border-bottom:1px solid rgba(13,27,42,0.08);text-align:center}' +
                'tr.alt td{background:#fafbfd}' +
                'td.num{font-variant-numeric:tabular-nums;font-weight:600}' +
                'td.solde{color:#c47e00}' +
                'tr.totals-row td{background:linear-gradient(90deg,#eef2f8,#f8fafc);border-top:2px solid #14213d;border-bottom:none;padding:12px 8px}' +
                'td.highlight{color:#c47e00;font-size:13px}' +
                '.foot{margin-top:20px;padding-top:14px;border-top:1px dashed rgba(13,27,42,0.15);font-size:11px;color:#5a6570;text-align:center}' +
                '@media print{body{background:#fff;padding:0}.sheet{box-shadow:none;border-radius:0}}';

            var printBody =
                '<div class="sheet">' +
                '<header class="head">' +
                '<div class="company">' + escapeHtml(COMPANY_NAME) + '</div>' +
                '<div class="doc-title">Balance des Ventes</div>' +
                '</header>' +
                '<div class="meta">' +
                '<div class="meta-item"><label>N° État Balance</label><span>' + escapeHtml(etatNum) + '</span></div>' +
                '<div class="meta-item"><label>Date de</label><span>' + escapeHtml(range.from ? formatMonthLabel(range.from) : '—') + '</span></div>' +
                '<div class="meta-item"><label>Date à</label><span>' + escapeHtml(range.to ? formatMonthLabel(range.to) : '—') + '</span></div>' +
                '<div class="meta-item"><label>Date impression</label><span>' + escapeHtml(printDate) + '</span></div>' +
                '</div>' +
                '<div class="content">' +
                summaryCards +
                '<table><thead><tr>' +
                '<th>Date</th><th>N° Bn</th><th>Montant d\'Achat</th><th>Montant de Vente</th><th>Montant Payé</th><th>Solde</th>' +
                '</tr></thead><tbody>' +
                (htmlRows || '<tr><td colspan="6">Aucune donnée</td></tr>') +
                (rows.length ? totalsRow : '') +
                '</tbody></table>' +
                '<p class="foot">Période : ' + escapeHtml(periode) + ' — Document généré par ' + escapeHtml(COMPANY_NAME) + '</p>' +
                '</div></div>';

            var w = window.open('', '_blank', 'width=1024,height=800');
            if (!w) {
                window.print();
                return;
            }
            w.document.write(
                '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Balance des Ventes — ' + escapeHtml(etatNum) + '</title>' +
                '<style>' + printCss + '</style></head><body>' + printBody + '</body></html>'
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
        if (window.StockStore && StockStore.initCatalogFromServer) {
            StockStore.initCatalogFromServer().then(renderBalance);
        }
    </script>
</body>
</html>
