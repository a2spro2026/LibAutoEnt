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
        .search-field input.date-fr {
            width: min(100%, 148px);
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.02em;
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

        .print-preview {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 200;
            background: #e8edf2;
            overflow-y: auto;
            padding: 1rem;
        }
        .print-preview.is-open { display: block; }
        .print-preview-toolbar {
            position: sticky;
            top: 0;
            z-index: 2;
            display: flex;
            gap: 0.65rem;
            justify-content: center;
            padding: 0.85rem;
            margin-bottom: 1rem;
            background: rgba(255,255,255,0.95);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(13,27,42,0.12);
        }
        .print-preview-inner {
            max-width: 980px;
            margin: 0 auto 2rem;
        }

        .print-sheet {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(13,27,42,0.12);
            color: #0d1b2a;
            font-family: "Segoe UI", Arial, sans-serif;
        }
        .print-head {
            background: linear-gradient(135deg, #0d1b2a 0%, #14213d 55%, #1b4332 100%);
            color: #fff;
            padding: 28px 32px 24px;
            border-bottom: 4px solid #fca311;
        }
        .print-company { font-size: 26px; font-weight: 800; margin-bottom: 4px; }
        .print-doc-title {
            font-size: 15px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.12em; color: #fca311;
        }
        .print-meta {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            padding: 18px 32px;
            background: #f4f7fb;
            border-bottom: 1px solid rgba(13,27,42,0.08);
        }
        .print-meta label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #5a6570;
            margin-bottom: 4px;
        }
        .print-meta span { font-size: 13px; font-weight: 600; }
        .print-content { padding: 24px 32px 32px; }
        .print-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 22px;
        }
        .print-summary-card {
            background: #f8fafc;
            border: 1px solid rgba(13,27,42,0.08);
            border-radius: 10px;
            padding: 12px 14px;
            text-align: center;
        }
        .print-summary-card span {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #5a6570;
            margin-bottom: 6px;
        }
        .print-summary-card strong { font-size: 15px; font-weight: 800; }
        .print-summary-card.accent {
            background: linear-gradient(145deg, #fff8eb, #fff3d6);
            border-color: rgba(252,163,17,0.35);
        }
        .print-summary-card.accent strong { color: #c47e00; }
        .print-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .print-table th {
            background: #14213d;
            color: #fff;
            padding: 10px 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .print-table td {
            padding: 9px 8px;
            border-bottom: 1px solid rgba(13,27,42,0.08);
            text-align: center;
        }
        .print-table tr.alt td { background: #fafbfd; }
        .print-table td.num { font-variant-numeric: tabular-nums; font-weight: 600; }
        .print-table td.solde { color: #c47e00; }
        .print-table tr.totals-row td {
            background: #eef2f8;
            border-top: 2px solid #14213d;
            font-weight: 700;
        }
        .print-table td.highlight { color: #c47e00; }
        .print-foot {
            margin-top: 20px;
            padding-top: 14px;
            border-top: 1px dashed rgba(13,27,42,0.15);
            font-size: 11px;
            color: #5a6570;
            text-align: center;
        }

        @media (max-width: 900px) {
            .balance-stats { grid-template-columns: 1fr; }
            .page-wrap { padding: 0 1rem 1.25rem; }
            .toolbar { flex-direction: column; align-items: stretch; }
            .search-field input { width: 100%; }
            .toolbar-actions { justify-content: flex-end; }
        }
        @media print {
            .sidebar, .topbar, .toolbar, .overlay, .menu-toggle, .topbar-badge,
            .balance-stats, .period-hint, .app, .print-preview-toolbar { display: none !important; }
            .print-preview {
                display: block !important;
                position: static !important;
                inset: auto !important;
                background: #fff !important;
                padding: 0 !important;
                overflow: visible !important;
            }
            .print-preview-inner { max-width: none !important; margin: 0 !important; }
            .print-sheet { box-shadow: none !important; border-radius: 0 !important; }
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
                            <label for="filterDateDe">De</label>
                            <input type="text" class="date-fr" id="filterDateDe" inputmode="numeric" placeholder="jj/mm/aaaa" maxlength="10" autocomplete="off" spellcheck="false">
                        </div>
                        <div class="search-field">
                            <label for="filterDateA">À</label>
                            <input type="text" class="date-fr" id="filterDateA" inputmode="numeric" placeholder="jj/mm/aaaa" maxlength="10" autocomplete="off" spellcheck="false">
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

    <div class="print-preview" id="printPreview" aria-hidden="true">
        <div class="print-preview-toolbar">
            <button type="button" class="btn btn-print" id="btnPrintConfirm">Imprimer</button>
            <button type="button" class="btn btn-close" id="btnPrintClose">Fermer</button>
        </div>
        <div class="print-preview-inner" id="printPreviewContent"></div>
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
        const DEFAULT_YEAR = 2026;
        const filterDateDe = document.getElementById('filterDateDe');
        const filterDateA = document.getElementById('filterDateA');
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

        function formatDateFr(dateStr) {
            if (!dateStr || dateStr === '—') return '—';
            dateStr = String(dateStr).trim();
            if (dateStr.indexOf('-') !== -1) {
                var iso = dateStr.slice(0, 10).split('-');
                if (iso.length === 3 && iso[0].length === 4) {
                    return String(iso[2]).padStart(2, '0') + '/' +
                        String(iso[1]).padStart(2, '0') + '/' +
                        iso[0];
                }
            }
            var p = dateStr.split('/');
            if (p.length === 3) {
                var dd = String(p[0]).padStart(2, '0');
                var mm = String(p[1]).padStart(2, '0');
                var yyyy = String(p[2] || '').trim();
                if (yyyy.length === 2) yyyy = '20' + yyyy;
                if (!yyyy || !/^\d{4}$/.test(yyyy)) yyyy = String(DEFAULT_YEAR);
                return dd + '/' + mm + '/' + yyyy;
            }
            return dateStr;
        }

        function parseBonDateTs(dateStr) {
            if (!dateStr) return 0;
            dateStr = String(dateStr).trim();
            if (dateStr.indexOf('-') !== -1) {
                var iso = dateStr.slice(0, 10).split('-');
                if (iso.length !== 3) return 0;
                return new Date(Number(iso[0]), Number(iso[1]) - 1, Number(iso[2])).getTime() || 0;
            }
            var p = dateStr.split('/');
            if (p.length !== 3) return 0;
            var yyyy = String(p[2] || '').trim();
            if (yyyy.length === 2) yyyy = '20' + yyyy;
            if (!yyyy || !/^\d{4}$/.test(yyyy)) yyyy = String(DEFAULT_YEAR);
            return new Date(Number(yyyy), Number(p[1]) - 1, Number(p[0])).getTime() || 0;
        }

        function isoToTs(iso) {
            if (!iso) return 0;
            var p = iso.split('-');
            if (p.length !== 3) return 0;
            return new Date(Number(p[0]), Number(p[1]) - 1, Number(p[2])).getTime() || 0;
        }

        function dateDigits(value) {
            return String(value || '').replace(/\D/g, '').slice(0, 8);
        }

        function maskFrDateInput(el) {
            if (!el) return;
            var d = dateDigits(el.value);
            if (!d) {
                el.value = '';
                return;
            }
            if (d.length <= 2) {
                el.value = d;
            } else if (d.length <= 4) {
                el.value = d.slice(0, 2) + '/' + d.slice(2);
            } else {
                el.value = d.slice(0, 2) + '/' + d.slice(2, 4) + '/' + d.slice(4);
            }
        }

        function normalizeFrDateInput(value) {
            value = String(value || '').trim();
            if (!value) return '';

            var digits = dateDigits(value);
            if (digits.length === 8) {
                value = digits.slice(0, 2) + '/' + digits.slice(2, 4) + '/' + digits.slice(4);
            }

            var p = value.split('/');
            if (p.length === 2 && p[0] && p[1]) {
                return String(p[0]).padStart(2, '0') + '/' +
                    String(p[1]).padStart(2, '0') + '/' + DEFAULT_YEAR;
            }
            if (p.length !== 3 || !p[0] || !p[1]) return value;

            var dd = String(p[0]).padStart(2, '0');
            var mm = String(p[1]).padStart(2, '0');
            var yyyy = String(p[2] || '').trim();
            if (!yyyy) yyyy = String(DEFAULT_YEAR);
            if (yyyy.length === 2) yyyy = '20' + yyyy;
            return dd + '/' + mm + '/' + yyyy;
        }

        function isValidFrDate(value) {
            if (!value) return true;
            var normalized = normalizeFrDateInput(value);
            if (!/^\d{2}\/\d{2}\/\d{4}$/.test(normalized)) return false;
            var ts = parseBonDateTs(normalized);
            if (!ts) return false;
            var parts = normalized.split('/');
            var day = Number(parts[0]);
            var month = Number(parts[1]);
            var year = Number(parts[2]);
            var check = new Date(year, month - 1, day);
            return check.getFullYear() === year &&
                check.getMonth() === month - 1 &&
                check.getDate() === day;
        }

        function getFilterDateValue(el) {
            if (!el) return '';
            var value = String(el.value || '').trim();
            if (!value) return '';
            var normalized = normalizeFrDateInput(value);
            if (!isValidFrDate(normalized)) return '';
            return normalized;
        }

        function prepareDateFilters() {
            [filterDateDe, filterDateA].forEach(function (el) {
                if (!el || !el.value.trim()) return;
                el.value = normalizeFrDateInput(el.value);
                el.style.borderColor = isValidFrDate(el.value) ? '' : '#c62828';
            });
        }

        function showPrintPreview(html) {
            var preview = document.getElementById('printPreview');
            var content = document.getElementById('printPreviewContent');
            if (!preview || !content) return;
            content.innerHTML = html;
            preview.classList.add('is-open');
            preview.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closePrintPreview() {
            var preview = document.getElementById('printPreview');
            if (!preview) return;
            preview.classList.remove('is-open');
            preview.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        function formatIsoFr(iso) {
            return formatDateFr(iso);
        }

        function getDateRange() {
            var from = getFilterDateValue(filterDateDe);
            var to = getFilterDateValue(filterDateA);
            if (from && to && parseBonDateTs(from) > parseBonDateTs(to)) {
                var tmp = from;
                from = to;
                to = tmp;
            }
            return { from: from, to: to };
        }

        function bonInDateRange(bon, range) {
            if (!range.from && !range.to) return true;
            var ts = parseBonDateTs(bon && bon.date);
            if (!ts) return false;
            if (range.from && ts < parseBonDateTs(range.from)) return false;
            if (range.to && ts > parseBonDateTs(range.to)) return false;
            return true;
        }

        function hasActiveFilters() {
            var range = getDateRange();
            return !!(range.from || range.to);
        }

        function periodLabel() {
            var range = getDateRange();
            if (!range.from && !range.to) return '';
            if (range.from && range.to) {
                if (range.from === range.to) return 'Période : ' + formatIsoFr(range.from);
                return 'Période : De ' + formatIsoFr(range.from) + ' à ' + formatIsoFr(range.to);
            }
            if (range.from) return 'Période : À partir du ' + formatIsoFr(range.from);
            return 'Période : Jusqu’au ' + formatIsoFr(range.to);
        }

        function periodPrintLabel() {
            var label = periodLabel();
            return label ? label.replace(/^Période\s*:\s*/, '') : 'Toutes périodes';
        }

        function getPrintPeriodBounds() {
            var range = getDateRange();
            return {
                de: range.from ? formatIsoFr(range.from) : '—',
                a: range.to ? formatIsoFr(range.to) : '—'
            };
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
            return String(d.getDate()).padStart(2, '0') + '/' +
                String(d.getMonth() + 1).padStart(2, '0') + '/' +
                d.getFullYear();
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
            var bons = (window.VenteStore && VenteStore.getBons) ? VenteStore.getBons() : [];
            return bons.filter(function (b) {
                return bonInDateRange(b, getDateRange());
            }).map(function (b) {
                var achat = montantAchatBon(b, map);
                var vente = Number(b.montant) || 0;
                var paye = Number(b.montantPaye) || 0;
                var solde = b.solde != null ? Number(b.solde) : Math.max(0, vente - paye);
                return {
                    date: formatDateFr(b.date),
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
            var filtered = hasActiveFilters();
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

        function bindDateFilter(el) {
            if (!el) return;
            el.addEventListener('input', function () {
                maskFrDateInput(el);
                renderBalance();
            });
            el.addEventListener('blur', function () {
                if (!el.value.trim()) {
                    renderBalance();
                    return;
                }
                el.value = normalizeFrDateInput(el.value);
                if (!isValidFrDate(el.value)) {
                    el.style.borderColor = '#c62828';
                } else {
                    el.style.borderColor = '';
                }
                renderBalance();
            });
        }

        bindDateFilter(filterDateDe);
        bindDateFilter(filterDateA);

        document.getElementById('btnReset').addEventListener('click', function () {
            if (filterDateDe) {
                filterDateDe.value = '';
                filterDateDe.style.borderColor = '';
            }
            if (filterDateA) {
                filterDateA.value = '';
                filterDateA.style.borderColor = '';
            }
            renderBalance();
        });

        document.getElementById('btnImprimer').addEventListener('click', function () {
            prepareDateFilters();
            var rows = getBalanceRows();
            var totals = computeTotals(rows);
            var etatNum = balanceEtatNumber();
            var periode = periodPrintLabel();
            var printDate = formatPrintDate();
            var bounds = getPrintPeriodBounds();

            if (!rows.length) {
                alert('Aucun bon à imprimer pour cette période. Vérifiez les dates De/À ou cliquez Reset.');
                return;
            }

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

            var printBody =
                '<div class="print-sheet">' +
                '<header class="print-head">' +
                '<div class="print-company">' + escapeHtml(COMPANY_NAME) + '</div>' +
                '<div class="print-doc-title">Balance des Ventes</div>' +
                '</header>' +
                '<div class="print-meta">' +
                '<div><label>N° État Balance</label><span>' + escapeHtml(etatNum) + '</span></div>' +
                '<div><label>Date de</label><span>' + escapeHtml(bounds.de) + '</span></div>' +
                '<div><label>Date à</label><span>' + escapeHtml(bounds.a) + '</span></div>' +
                '<div><label>Date impression</label><span>' + escapeHtml(printDate) + '</span></div>' +
                '</div>' +
                '<div class="print-content">' +
                '<div class="print-summary">' +
                '<div class="print-summary-card"><span>Total Achats</span><strong>' + escapeHtml(money(totals.achats)) + '</strong></div>' +
                '<div class="print-summary-card accent"><span>Total Ventes</span><strong>' + escapeHtml(money(totals.ventes)) + '</strong></div>' +
                '<div class="print-summary-card"><span>Total Marge</span><strong>' + escapeHtml(money(totals.marge)) + '</strong></div>' +
                '<div class="print-summary-card"><span>Total Payé</span><strong>' + escapeHtml(money(totals.paye)) + '</strong></div>' +
                '</div>' +
                '<table class="print-table"><thead><tr>' +
                '<th>Date</th><th>N° Bn</th><th>Montant d\'Achat</th><th>Montant de Vente</th><th>Montant Payé</th><th>Solde</th>' +
                '</tr></thead><tbody>' +
                htmlRows +
                totalsRow +
                '</tbody></table>' +
                '<p class="print-foot">Période : ' + escapeHtml(periode) + ' — Document généré par ' + escapeHtml(COMPANY_NAME) + '</p>' +
                '</div></div>';

            showPrintPreview(printBody);
        });

        document.getElementById('btnPrintConfirm').addEventListener('click', function () {
            window.print();
        });
        document.getElementById('btnPrintClose').addEventListener('click', closePrintPreview);
        document.getElementById('printPreview').addEventListener('click', function (e) {
            if (e.target === document.getElementById('printPreview')) closePrintPreview();
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
