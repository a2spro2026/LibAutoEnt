<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Balance Vente — LibAutoEnt</title>
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

        .analytics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .ana-card {
            position: relative;
            padding: 1.15rem 1.25rem 1.2rem;
            border-radius: 18px;
            background: var(--white);
            border: 1px solid rgba(252, 163, 17, 0.16);
            box-shadow: var(--shadow-card);
            overflow: hidden;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .ana-card::before {
            content: '';
            position: absolute;
            inset: 0 auto 0 0;
            width: 5px;
            background: var(--tone, var(--green-bright));
        }
        .ana-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px rgba(7, 17, 28, 0.12);
        }
        .ana-card[data-tone="achats"] { --tone: #fca311; }
        .ana-card[data-tone="paye"] { --tone: #3d7ea6; }
        .ana-card[data-tone="solde"] { --tone: #c62828; }

        .ana-label {
            font-family: 'Outfit', sans-serif;
            font-size: 0.82rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.45rem;
        }
        .ana-value {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.25rem, 2.2vw, 1.55rem);
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -0.02em;
        }
        .ana-card[data-tone="solde"] .ana-value { color: #c62828; }
        .ana-hint {
            margin-top: 0.35rem;
            font-size: 0.78rem;
            color: var(--muted);
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
            min-width: 780px;
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

        .actions {
            display: flex;
            gap: 0.35rem;
            justify-content: center;
            flex-wrap: wrap;
            position: relative;
            z-index: 2;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            border: 1px solid rgba(13, 27, 42, 0.1);
            background: #f4f7f0;
            color: var(--ink-soft);
            display: inline-grid;
            place-items: center;
            cursor: pointer;
            pointer-events: auto !important;
            position: relative;
            z-index: 10;
            transition: background 0.15s, color 0.15s, box-shadow 0.15s;
        }
        .icon-btn svg,
        .icon-btn svg * {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            pointer-events: none !important;
        }
        .icon-btn:hover { box-shadow: 0 4px 12px rgba(21,32,20,0.1); }
        .icon-btn.view:hover,
        .icon-btn.icon-view:hover { background: rgba(61, 126, 166, 0.12); color: #3d7ea6; }
        .icon-btn.print:hover,
        .icon-btn.icon-print:hover { background: rgba(47, 143, 107, 0.14); color: #2f8f6b; }

        /* Modal détail */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(10, 16, 8, 0.55);
            backdrop-filter: blur(4px);
            z-index: 80;
            display: none;
            pointer-events: none;
            align-items: flex-start;
            justify-content: center;
            padding: 1.25rem;
            overflow-y: auto;
        }
        .modal-backdrop.show { display: flex; pointer-events: auto; }

        .modal {
            width: min(100%, 860px);
            margin: 1.5rem auto;
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(7, 17, 28, 0.35), 0 0 0 1px rgba(252, 163, 17, 0.2);
            overflow: hidden;
        }
        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.1rem 1.35rem;
            background: linear-gradient(125deg, #14213d, #0d1b2a 60%, #243016);
            color: #fff;
        }
        .modal-head h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
        }
        .modal-head h2 span { color: var(--gold); }
        .modal-body { padding: 1.25rem 1.35rem 1.4rem; }

        .modal-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            justify-content: flex-end;
            margin-top: 1.1rem;
        }

        .detail-table { min-width: 640px; }

        @media (max-width: 900px) {
            .page-wrap { padding: 0 1rem 1.25rem; }
            .analytics { grid-template-columns: 1fr; }
        }

        @media print {
            .sidebar, .topbar, .toolbar, .modal-actions, .actions, .overlay, .menu-toggle { display: none !important; }
            .app { display: block !important; }
            .main { margin: 0 !important; width: 100% !important; }
            .modal-backdrop { position: static; display: block !important; background: none; padding: 0; }
            .modal { box-shadow: none; border-radius: 0; width: 100%; }
            .analytics { break-inside: avoid; }
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
                        <h1>Balance Vente</h1>
                    </div>
                </div>
                <div class="topbar-badge"><i></i> Session active</div>
            </header>

            <div class="page-wrap">
                <div class="analytics" aria-label="Cartes analytiques">
                    <div class="ana-card" data-tone="achats">
                        <div class="ana-label">Total Ventes</div>
                        <div class="ana-value" id="cardTotalAchats">0.00 DH</div>
                        <div class="ana-hint">Montant cumulé des bons</div>
                    </div>
                    <div class="ana-card" data-tone="paye">
                        <div class="ana-label">Total Payé</div>
                        <div class="ana-value" id="cardTotalPaye">0.00 DH</div>
                        <div class="ana-hint">Règlements effectués</div>
                    </div>
                    <div class="ana-card" data-tone="solde">
                        <div class="ana-label">Total Solde</div>
                        <div class="ana-value" id="cardTotalSolde">0.00 DH</div>
                        <div class="ana-hint">Reste à payer</div>
                    </div>
                </div>

                <div class="toolbar">
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
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Montant Payé</th>
                                    <th>Solde</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="balanceBody">
                                <tr class="empty-row">
                                    <td colspan="6" class="empty">Aucune donnée — créez des bons de vente pour afficher la balance</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Détail client --}}
    <div class="modal-backdrop" id="modalDetail" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <h2>Détail <span id="detailClientTitle">Client</span></h2>
                <button type="button" class="btn btn-close" id="detailModalX" style="padding:0.45rem 0.7rem;">Fermer</button>
            </div>
            <div class="modal-body">
                <div class="table-card" style="box-shadow:none;border-radius:12px;">
                    <div class="table-scroll">
                        <table class="data-table detail-table" id="detailTable">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Bon N°</th>
                                    <th>Montant Bon</th>
                                    <th>Montant Payé</th>
                                    <th>Solde</th>
                                </tr>
                            </thead>
                            <tbody id="detailBody">
                                <tr class="empty-row">
                                    <td colspan="5" class="empty">Aucun bon</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-print" id="btnDetailPrint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V3h12v6"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v7H6z"/></svg>
                        Imprimer
                    </button>
                    <button type="button" class="btn btn-close" id="btnDetailFermer">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table-actions.js') }}?v=5"></script>
    <script src="{{ asset('js/vente-store.js') }}?v=7"></script>
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

        const modalDetail = document.getElementById('modalDetail');
        const detailBody = document.getElementById('detailBody');
        const detailClientTitle = document.getElementById('detailClientTitle');
        let currentClient = '';

        function updateCards() {
            if (!window.VenteStore) return;
            var t = VenteStore.getBalanceTotals();
            document.getElementById('cardTotalAchats').textContent = VenteStore.fmtMoney(t.montant);
            document.getElementById('cardTotalPaye').textContent = VenteStore.fmtMoney(t.montantPaye);
            document.getElementById('cardTotalSolde').textContent = VenteStore.fmtMoney(t.solde);
        }

        function renderBalance() {
            var body = document.getElementById('balanceBody');
            if (!body || !window.VenteStore) return;

            var rows = VenteStore.getBalanceClients();
            updateCards();

            if (!rows.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="6" class="empty">Aucune donnée — créez des bons de vente pour afficher la balance</td></tr>';
                return;
            }

            body.innerHTML = rows.map(function (r) {
                return '' +
                    '<tr data-client="' + String(r.client).replace(/"/g, '&quot;') + '">' +
                    '<td>' + (r.date || '—') + '</td>' +
                    '<td>' + (r.client || '—') + '</td>' +
                    '<td>' + VenteStore.fmtMoney(r.montant) + '</td>' +
                    '<td>' + VenteStore.fmtMoney(r.montantPaye) + '</td>' +
                    '<td class="col-solde">' + VenteStore.fmtMoney(r.solde) + '</td>' +
                    '<td class="actions-cell"></td>' +
                    '</tr>';
            }).join('');

            if (window.TableActions) {
                TableActions.fillCells('#balanceBody .actions-cell', ['view', 'print']);
            }
        }

        function openDetail(client) {
            if (!window.VenteStore || !client) return;
            currentClient = client;
            detailClientTitle.textContent = client;

            var bons = VenteStore.getBonsByClient(client);
            if (!bons.length) {
                detailBody.innerHTML = '<tr class="empty-row"><td colspan="5" class="empty">Aucun bon pour ce client</td></tr>';
            } else {
                detailBody.innerHTML = bons.map(function (b) {
                    return '' +
                        '<tr>' +
                        '<td>' + (b.client || '—') + '</td>' +
                        '<td>' + (b.numero || '—') + '</td>' +
                        '<td>' + VenteStore.fmtMoney(b.montant) + '</td>' +
                        '<td>' + VenteStore.fmtMoney(b.montantPaye) + '</td>' +
                        '<td class="col-solde">' + VenteStore.fmtMoney(b.solde) + '</td>' +
                        '</tr>';
                }).join('');
            }

            modalDetail.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeDetail() {
            modalDetail.classList.remove('show');
            document.body.style.overflow = '';
            currentClient = '';
        }

        function printDetail() {
            if (!currentClient || !window.VenteStore) {
                window.print();
                return;
            }
            var bons = VenteStore.getBonsByClient(currentClient);
            var rows = bons.map(function (b) {
                return '<tr>' +
                    '<td>' + (b.client || '') + '</td>' +
                    '<td>' + (b.numero || '') + '</td>' +
                    '<td>' + VenteStore.fmtMoney(b.montant) + '</td>' +
                    '<td>' + VenteStore.fmtMoney(b.montantPaye) + '</td>' +
                    '<td>' + VenteStore.fmtMoney(b.solde) + '</td>' +
                    '</tr>';
            }).join('');

            var w = window.open('', '_blank', 'width=900,height=700');
            if (!w) {
                alert('Autorisez les pop-ups pour imprimer.');
                return;
            }
            w.document.write(
                '<!DOCTYPE html><html lang="fr"><head><meta charset="utf-8"><title>Balance — ' + currentClient + '</title>' +
                '<style>body{font-family:Arial,sans-serif;padding:24px;color:#0d1b2a}h1{font-size:18px;margin:0 0 16px}' +
                'table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:8px;text-align:center;font-size:13px}' +
                'th{background:#14213d;color:#fff}.solde{color:#c62828;font-weight:700}</style></head><body>' +
                '<h1>Détail Balance Vente — ' + currentClient + '</h1>' +
                '<table><thead><tr><th>Client</th><th>Bon N°</th><th>Montant Bon</th><th>Montant Payé</th><th>Solde</th></tr></thead>' +
                '<tbody>' + rows + '</tbody></table>' +
                '<script>window.onload=function(){window.print();}</' + 'script></body></html>'
            );
            w.document.close();
        }

        function printRow(client) {
            currentClient = client;
            printDetail();
        }

        if (window.TableActions) {
            TableActions.setHandlers({
                view: function (tr) {
                    var frns = tr.getAttribute('data-client') || (tr.cells[1] ? tr.cells[1].textContent.trim() : '');
                    openDetail(frns);
                },
                print: function (tr) {
                    var frns = tr.getAttribute('data-client') || (tr.cells[1] ? tr.cells[1].textContent.trim() : '');
                    printRow(frns);
                }
            });
            TableActions.bind(document.getElementById('balanceBody'));
        }

        document.getElementById('detailModalX').addEventListener('click', closeDetail);
        document.getElementById('btnDetailFermer').addEventListener('click', closeDetail);
        document.getElementById('btnDetailPrint').addEventListener('click', printDetail);
        modalDetail.addEventListener('click', function (e) {
            if (e.target === modalDetail) closeDetail();
        });

        renderBalance();
    </script>
</body>
</html>
