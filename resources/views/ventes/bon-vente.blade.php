<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bon Vente — LibAutoEnt</title>
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
        .btn:hover:not(:disabled):not(.is-disabled) { transform: translateY(-1px); }
        .btn svg { width: 16px; height: 16px; }

        .btn:disabled,
        .btn.is-disabled {
            opacity: 0.45;
            filter: grayscale(0.55);
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
            pointer-events: none;
        }

        .btn-add {
            color: var(--ink);
            background: linear-gradient(135deg, #ffb83a, var(--green-bright) 50%, #e8920a);
            box-shadow: 0 0 18px rgba(252, 163, 17, 0.35), 0 6px 14px rgba(0,0,0,0.12);
        }
        .btn-close {
            color: #fff;
            background: linear-gradient(135deg, #5a6570, #3d4650);
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }
        .btn-validate {
            color: var(--ink);
            background: linear-gradient(135deg, #ffb83a, var(--green-bright));
            box-shadow: 0 0 16px rgba(252, 163, 17, 0.35);
        }
        .btn-pay {
            color: #1a1408;
            background: linear-gradient(135deg, #ffb83a, var(--gold));
            box-shadow: 0 0 16px rgba(252, 163, 17, 0.35);
        }
        .btn-ghost-dark {
            color: var(--ink-soft);
            background: var(--white);
            border: 1px solid rgba(13, 27, 42, 0.12);
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
            min-width: 900px;
        }

        .data-table th {
            text-align: center;
            padding: 1rem 0.85rem;
            font-family: 'Outfit', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #f4f9ee;
            background: linear-gradient(145deg, #14213d 0%, #07111c 48%, #3f7a22 100%);
            border-bottom: none;
            white-space: nowrap;
            box-shadow: inset 0 -1px 0 rgba(252, 163, 17, 0.35);
        }

        .data-table th + th {
            box-shadow:
                inset 1px 0 0 rgba(255, 255, 255, 0.08),
                inset 0 -1px 0 rgba(252, 163, 17, 0.35);
        }

        .data-table td {
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(13, 27, 42, 0.06);
            color: var(--ink);
            vertical-align: middle;
            text-align: center;
        }

        .data-table td.col-solde,
        .data-table th.col-solde {
            color: #c62828;
            font-weight: 700;
        }

        .data-table tbody tr:hover { background: rgba(252, 163, 17, 0.06); }

        .data-table .empty {
            text-align: center;
            color: var(--muted);
            padding: 2.5rem 1rem;
        }

        .actions {
            display: flex;
            gap: 0.4rem;
            justify-content: center;
        }

        .icon-btn {
            width: 34px;
            height: 34px;
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
            pointer-events: none !important;
        }
        .icon-btn:hover { box-shadow: 0 4px 12px rgba(21,32,20,0.1); }
        .icon-btn.view:hover { background: rgba(61, 126, 166, 0.12); color: #3d7ea6; }
        .icon-btn.edit:hover { background: rgba(252, 163, 17, 0.15); color: #a8861a; }
        .icon-btn.delete:hover { background: rgba(184, 92, 56, 0.12); color: #b85c38; }

        /* Modal */
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
        .modal-backdrop.show {
            display: flex;
            pointer-events: auto;
        }

        .modal {
            width: min(100%, 980px);
            margin: 1.5rem auto;
            background: var(--white);
            border-radius: 20px;
            box-shadow:
                0 24px 60px rgba(7, 17, 28, 0.35),
                0 0 0 1px rgba(252, 163, 17, 0.2),
                0 0 40px rgba(252, 163, 17, 0.12);
            overflow: hidden;
            animation: modal-in 0.28s ease;
        }

        @keyframes modal-in {
            from { opacity: 0; transform: translateY(12px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
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
            font-size: 1.2rem;
            font-weight: 700;
        }
        .modal-head h2 span { color: var(--gold); }

        .modal-body { padding: 1.25rem 1.35rem 1.4rem; }

        .form-grid {
            display: grid;
            grid-template-columns: minmax(120px, 1.1fr) minmax(90px, 0.7fr) minmax(160px, 1.8fr);
            gap: 0.65rem;
            margin-bottom: 1.15rem;
            align-items: end;
        }
        .field-narrow input,
        .field-narrow select {
            padding-left: 0.45rem;
            padding-right: 0.45rem;
            font-size: 0.84rem;
        }

        .field label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .field input,
        .field select {
            width: 100%;
            padding: 0.7rem 0.85rem;
            border-radius: 10px;
            border: 1px solid rgba(13, 27, 42, 0.12);
            background: #f7faf3;
            font-family: inherit;
            font-size: 0.92rem;
            color: var(--ink);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .field input:focus,
        .field select:focus {
            border-color: rgba(252, 163, 17, 0.65);
            box-shadow: 0 0 0 3px rgba(252, 163, 17, 0.15);
            background: #fff;
        }

        .field input.readonly {
            background: #eef2f7;
            font-weight: 600;
            color: var(--ink);
            cursor: default;
        }

        .lines-wrap {
            border: 1px solid rgba(13, 27, 42, 0.08);
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .lines-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px;
        }

        .lines-table th {
            padding: 0.65rem 0.7rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--muted);
            background: #eef2f7;
            text-align: center;
            white-space: nowrap;
        }

        .lines-table td {
            padding: 0.45rem 0.5rem;
            border-top: 1px solid rgba(13, 27, 42, 0.06);
            vertical-align: middle;
            text-align: center;
        }

        .lines-table input,
        .lines-table select {
            width: 100%;
            padding: 0.55rem 0.6rem;
            border-radius: 8px;
            border: 1px solid rgba(13, 27, 42, 0.1);
            background: #fff;
            font-family: inherit;
            font-size: 0.85rem;
            outline: none;
            text-align: center;
        }

        .lines-table input:focus,
        .lines-table select:focus {
            border-color: rgba(252, 163, 17, 0.65);
        }

        .lines-table .subtotal,
        .lines-table .readonly {
            background: #f0f3f7;
            font-weight: 600;
        }

        .btn-plus {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            color: #0d1b2a !important;
            background: linear-gradient(135deg, #ffc857 0%, #fca311 45%, #e8920a 100%) !important;
            box-shadow: 0 0 14px rgba(252, 163, 17, 0.45), 0 4px 10px rgba(0, 0, 0, 0.12);
            display: grid;
            place-items: center;
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1;
            opacity: 1 !important;
            filter: none !important;
            -webkit-appearance: none;
            appearance: none;
            flex-shrink: 0;
        }

        .btn-plus:hover,
        .btn-plus:focus,
        .btn-plus:active {
            opacity: 1 !important;
            filter: brightness(1.06) !important;
            color: #0d1b2a !important;
            background: linear-gradient(135deg, #c5f06e 0%, #98d655 45%, #7aba3c 100%) !important;
        }

        .btn-plus:disabled {
            opacity: 1 !important;
            cursor: pointer;
            color: #0d1b2a !important;
            background: linear-gradient(135deg, #ffc857 0%, #fca311 45%, #e8920a 100%) !important;
        }

        .add-line-bar {
            display: flex;
            justify-content: flex-end;
            padding: 0.65rem 0.75rem;
            background: #f7faf3;
            border-top: 1px solid rgba(13, 27, 42, 0.06);
        }

        .btn-add-line {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 0.95rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.88rem;
            color: #0d1b2a;
            background: linear-gradient(135deg, #ffc857, #fca311);
            box-shadow: 0 0 14px rgba(252, 163, 17, 0.4);
        }

        .btn-row-del {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid rgba(184, 92, 56, 0.25);
            background: rgba(184, 92, 56, 0.08);
            color: #b85c38;
            cursor: pointer;
            display: grid;
            place-items: center;
        }
        .btn-row-del svg { width: 14px; height: 14px; }

        .total-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.15rem;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(252, 163, 17, 0.12), rgba(252, 163, 17, 0.1));
            border: 1px solid rgba(252, 163, 17, 0.22);
        }

        .total-bar span {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
        }

        .total-bar strong {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            color: var(--ink);
        }

        .modal-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            justify-content: flex-end;
        }

        .lines-scroll { overflow-x: auto; }

        @media (max-width: 900px) {
            .page-wrap { padding: 0 1rem 1.25rem; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="notranslate" translate="no">
    <div class="overlay" id="overlay"></div>

    <div class="app">
        @include('partials.sidebar', ['activePage' => 'bon-vente', 'openMenu' => 'client'])

        <div class="main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div class="topbar-left">
                        <h1>Bon Vente</h1>
                    </div>
                </div>
                <div class="topbar-badge"><i></i> Session active</div>
            </header>

            <div class="page-wrap">
                <div class="toolbar">
                    <button type="button" class="btn btn-add" id="btnAjouter">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg>
                        Ajouter
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-close">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6 6 18" stroke-linecap="round"/></svg>
                        Fermer
                    </a>
                </div>

                <div class="table-card">
                    <div class="table-scroll">
                        <table class="data-table" id="bonsTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Bon n°</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Type Paie</th>
                                    <th>Montant Payé</th>
                                    <th class="col-solde">Solde</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="bonsBody">
                                <tr class="empty-row">
                                    <td colspan="8" class="empty">Aucun bon de vente — cliquez sur Ajouter</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Fenêtre de saisie --}}
    <div class="modal-backdrop" id="modalBon" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="modal">
            <div class="modal-head">
                <h2 id="modalTitle">Nouveau <span>Bon Vente</span></h2>
                <button type="button" class="btn btn-close" id="modalX" style="padding:0.45rem 0.7rem;">Fermer</button>
            </div>
            <div class="modal-body">
                <form id="formBon" autocomplete="off">
                    <div class="form-grid">
                        <div class="field">
                            <label for="dateBon">Date</label>
                            <input type="date" id="dateBon" name="date" required>
                        </div>
                        <div class="field field-narrow">
                            <label for="numBon">N° Bn</label>
                            <input type="text" id="numBon" name="numero" placeholder="N°" value="">
                        </div>
                        <div class="field">
                            <label for="client">Nom Client</label>
                            <input type="text" id="client" name="client" placeholder="Nom du client" required>
                        </div>
                    </div>

                    <div class="lines-wrap">
                        <div class="lines-scroll">
                            <table class="lines-table">
                                <thead>
                                    <tr>
                                        <th>Article (Stock)</th>
                                        <th>Catégorie</th>
                                        <th style="width:90px">Qte</th>
                                        <th style="width:110px">P/U</th>
                                        <th style="width:120px">Sous-Total</th>
                                        <th style="width:48px">+</th>
                                    </tr>
                                </thead>
                                <tbody id="linesBody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="total-bar">
                        <span>Total</span>
                        <strong id="grandTotal">0.00 DH</strong>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-validate" id="btnValider">Valider</button>
                        <button type="button" class="btn btn-pay" id="btnPayer">Payer</button>
                        <button type="button" class="btn btn-ghost-dark" id="btnFermerModal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Fenêtre Paiement --}}
    <div class="modal-backdrop" id="modalPay" role="dialog" aria-modal="true" aria-labelledby="modalPayTitle">
        <div class="modal" style="width:min(100%,720px)">
            <div class="modal-head">
                <h2 id="modalPayTitle">Paiement <span>Bon Vente</span></h2>
                <button type="button" class="btn btn-close" id="payModalX" style="padding:0.45rem 0.7rem;">Fermer</button>
            </div>
            <div class="modal-body">
                <div class="form-grid" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
                    <div class="field">
                        <label for="payNumBon">N° Bon</label>
                        <input type="text" id="payNumBon" class="readonly" readonly tabindex="-1">
                    </div>
                    <div class="field">
                        <label for="payClient">Client</label>
                        <input type="text" id="payClient" class="readonly" readonly tabindex="-1">
                    </div>
                    <div class="field">
                        <label for="payMontantBon">Montant Bon</label>
                        <input type="text" id="payMontantBon" class="readonly" readonly tabindex="-1">
                    </div>
                </div>

                <div class="lines-wrap">
                    <div class="lines-scroll">
                        <table class="lines-table">
                            <thead>
                                <tr>
                                    <th>Montant Payé</th>
                                    <th>Type</th>
                                    <th>Solde</th>
                                    <th style="width:48px">+</th>
                                </tr>
                            </thead>
                            <tbody id="payLinesBody"></tbody>
                        </table>
                    </div>
                    <div class="add-line-bar">
                        <button type="button" class="btn-add-line" id="btnAddPayLine">+ Ajouter un paiement</button>
                    </div>
                </div>

                <div class="total-bar">
                    <span>Solde restant</span>
                    <strong id="paySoldeRestant">0.00 DH</strong>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-validate" id="btnPayValider">Valider</button>
                    <button type="button" class="btn btn-ghost-dark" id="btnPayFermer">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/data-sync.js') }}?v=4"></script>
    <script src="{{ asset('js/table-actions.js') }}?v=7"></script>
    <script src="{{ asset('js/stock-store.js') }}?v=10"></script>
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
            if (!modal.classList.contains('show') && !modalPay.classList.contains('show')) {
                document.body.style.overflow = '';
            }
        }
        menuToggle?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
        sidebarClose?.addEventListener('click', closeSidebar);
        overlay?.addEventListener('click', closeSidebar);

        /* Bon Vente */
        const modal = document.getElementById('modalBon');
        const modalPay = document.getElementById('modalPay');
        const linesBody = document.getElementById('linesBody');
        const payLinesBody = document.getElementById('payLinesBody');
        const bonsBody = document.getElementById('bonsBody');
        const grandTotalEl = document.getElementById('grandTotal');
        const btnValider = document.getElementById('btnValider');
        const btnPayer = document.getElementById('btnPayer');
        const btnFermerModal = document.getElementById('btnFermerModal');
        let currentBonMontant = 0;
        let bonValidated = false;

        const PAY_TYPES = ['Esp', 'Chq', 'Eff', 'Vir', 'En Compte', 'Crédit'];

        const fmt = (n) => Number(n || 0).toFixed(2);

        function setPayButtonsState(validated) {
            bonValidated = validated;
            btnValider.disabled = validated;
            btnValider.classList.toggle('is-disabled', validated);
            btnValider.textContent = validated ? 'Validé' : 'Valider';
        }

        function setFormLocked(locked) {
            document.querySelectorAll('#formBon input:not([readonly]), #formBon select, #formBon .btn-plus').forEach((el) => {
                if (['btnValider', 'btnPayer', 'btnFermerModal'].includes(el.id)) return;
                el.disabled = locked;
            });
        }

        function openModal() {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            const today = new Date().toISOString().slice(0, 10);
            document.getElementById('dateBon').value = today;
            document.getElementById('numBon').value = '';
            document.getElementById('client').value = '';
            linesBody.innerHTML = '';
            addLine();
            updateTotal();
            setFormLocked(false);
            setPayButtonsState(false);
        }

        function closeModal() {
            modal.classList.remove('show');
            if (!modalPay.classList.contains('show')) document.body.style.overflow = '';
            setFormLocked(false);
            setPayButtonsState(false);
        }

        function closePayModal() {
            modalPay.classList.remove('show');
            if (!modal.classList.contains('show')) document.body.style.overflow = '';
        }

        function validateBonForm() {
            const date = document.getElementById('dateBon').value;
            const num = document.getElementById('numBon').value.trim();
            const frns = document.getElementById('client').value.trim();
            if (!date || !frns) {
                alert('Veuillez renseigner Date et Client.');
                return null;
            }
            if (!linesAreValidFromStock()) {
                return null;
            }
            const lines = collectLines();
            const montant = updateTotal();
            if (!lines.length) {
                alert('Ajoutez au moins un produit du catalogue stock.');
                return null;
            }
            return { date, num, frns, lines, montant };
        }

        function openPayModal() {
            const data = validateBonForm();
            if (!data) return;

            currentBonMontant = data.montant;
            document.getElementById('payNumBon').value = data.num;
            document.getElementById('payClient').value = data.frns;
            document.getElementById('payMontantBon').value = fmt(data.montant) + ' DH';

            payLinesBody.innerHTML = '';
            addPayLine();
            updatePaySoldes();

            modalPay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function typeOptions(selected) {
            return PAY_TYPES.map((t) =>
                `<option value="${t}" ${t === selected ? 'selected' : ''}>${t}</option>`
            ).join('');
        }

        function addPayLine() {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input type="number" class="pay-montant" min="0" step="0.01" value="0"></td>
                <td>
                    <select class="pay-type">${typeOptions('Esp')}</select>
                </td>
                <td><input type="text" class="pay-solde readonly" readonly tabindex="-1" value="0.00"></td>
                <td style="text-align:center">
                    <button type="button" class="btn-plus" title="Ajouter un paiement">+</button>
                </td>
            `;
            payLinesBody.appendChild(tr);
            bindPayLine(tr);
            refreshPayPlusButtons();
            updatePaySoldes();
        }

        function refreshPayPlusButtons() {
            const rows = [...payLinesBody.querySelectorAll('tr')];
            rows.forEach((tr, index) => {
                const plus = tr.querySelector('.btn-plus');
                if (!plus) return;
                plus.style.display = index === rows.length - 1 ? 'grid' : 'none';
                plus.disabled = false;
            });
        }

        function bindPayLine(tr) {
            const montant = tr.querySelector('.pay-montant');
            const plus = tr.querySelector('.btn-plus');
            montant.addEventListener('input', updatePaySoldes);
            plus.addEventListener('click', (e) => {
                e.preventDefault();
                addPayLine();
            });
        }

        function getPayTotal() {
            let total = 0;
            payLinesBody.querySelectorAll('.pay-montant').forEach((input) => {
                total += parseFloat(input.value) || 0;
            });
            return total;
        }

        function getPayTypes() {
            const types = [];
            payLinesBody.querySelectorAll('tr').forEach((tr) => {
                const m = parseFloat(tr.querySelector('.pay-montant')?.value) || 0;
                if (m <= 0) return;
                const t = tr.querySelector('.pay-type')?.value;
                if (t && !types.includes(t)) types.push(t);
            });
            return types;
        }

        function updatePaySoldes() {
            let paidSoFar = 0;
            payLinesBody.querySelectorAll('tr').forEach((tr) => {
                const m = parseFloat(tr.querySelector('.pay-montant')?.value) || 0;
                paidSoFar += m;
                const solde = Math.max(0, currentBonMontant - paidSoFar);
                const soldeInput = tr.querySelector('.pay-solde');
                if (soldeInput) soldeInput.value = fmt(solde);
            });
            const restant = Math.max(0, currentBonMontant - paidSoFar);
            document.getElementById('paySoldeRestant').textContent = fmt(restant) + ' DH';
            return { paid: paidSoFar, solde: restant };
        }

        function getCatalogue() {
            return (window.StockStore && StockStore.getCatalogue) ? StockStore.getCatalogue() : [];
        }

        function productOptionsHtml(selectedId) {
            const list = getCatalogue();
            let html = '<option value="">— Choisir un article —</option>';
            list.forEach(function (p) {
                const id = p.id || '';
                const label = (p.ref ? p.ref + ' — ' : '') + (p.designation || 'Sans nom');
                const sel = selectedId && selectedId === id ? ' selected' : '';
                html += '<option value="' + id.replace(/"/g, '') + '"' + sel +
                    ' data-ref="' + String(p.ref || '').replace(/"/g, '&quot;') + '"' +
                    ' data-desig="' + String(p.designation || '').replace(/"/g, '&quot;') + '"' +
                    ' data-cat="' + String(p.categorie || '').replace(/"/g, '&quot;') + '"' +
                    ' data-pv="' + (Number(p.pv) || 0) + '">' +
                    label.replace(/</g, '&lt;') + '</option>';
            });
            return html;
        }

        function applyProductToLine(tr) {
            const sel = tr.querySelector('.prod');
            const opt = sel && sel.options[sel.selectedIndex];
            const cat = tr.querySelector('.cat');
            const pu = tr.querySelector('.pu');
            if (!opt || !opt.value) {
                if (cat) cat.value = '';
                if (pu) pu.value = '0.00';
                updateLineSub(tr);
                return null;
            }
            if (cat) cat.value = opt.getAttribute('data-cat') || '';
            if (pu) pu.value = fmt(opt.getAttribute('data-pv') || 0);
            updateLineSub(tr);
            return {
                id: opt.value,
                ref: opt.getAttribute('data-ref') || '',
                produit: opt.getAttribute('data-desig') || opt.textContent.trim(),
                categorie: opt.getAttribute('data-cat') || '',
                pu: parseFloat(opt.getAttribute('data-pv')) || 0
            };
        }

        function updateLineSub(tr) {
            const qte = tr.querySelector('.qte');
            const pu = tr.querySelector('.pu');
            const sub = tr.querySelector('.subtotal');
            const s = (parseFloat(qte?.value) || 0) * (parseFloat(pu?.value) || 0);
            if (sub) sub.value = fmt(s);
            updateTotal();
        }

        function addLine() {
            const list = getCatalogue();
            if (!list.length) {
                alert('Aucun article dans Catégorie Produit (Stock).\nAjoutez d’abord des produits au catalogue.');
            }
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <select class="prod" required>
                        ${productOptionsHtml('')}
                    </select>
                </td>
                <td><input type="text" class="cat readonly" readonly tabindex="-1" placeholder="Auto"></td>
                <td><input type="number" class="qte" min="0" step="any" value="1"></td>
                <td><input type="number" class="pu" min="0" step="0.01" value="0"></td>
                <td><input type="text" class="subtotal readonly" readonly tabindex="-1" value="0.00"></td>
                <td style="text-align:center">
                    <button type="button" class="btn-plus" title="Ajouter une ligne" aria-label="Ajouter une ligne">+</button>
                </td>
            `;
            linesBody.appendChild(tr);
            bindLine(tr);
            refreshPlusButtons();
        }

        function refreshPlusButtons() {
            const rows = [...linesBody.querySelectorAll('tr')];
            rows.forEach((tr, index) => {
                const plus = tr.querySelector('.btn-plus');
                if (!plus) return;
                const isLast = index === rows.length - 1;
                plus.style.display = isLast ? 'grid' : 'none';
                plus.disabled = false;
                plus.removeAttribute('disabled');
            });
        }

        function bindLine(tr) {
            const prod = tr.querySelector('.prod');
            const qte = tr.querySelector('.qte');
            const pu = tr.querySelector('.pu');
            const plus = tr.querySelector('.btn-plus');

            prod.addEventListener('change', function () {
                applyProductToLine(tr);
            });
            qte.addEventListener('input', function () { updateLineSub(tr); });
            pu.addEventListener('input', function () { updateLineSub(tr); });
            plus.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                addLine();
            });
        }

        document.getElementById('btnAddPayLine')?.addEventListener('click', (e) => {
            e.preventDefault();
            addPayLine();
        });

        function updateTotal() {
            let total = 0;
            linesBody.querySelectorAll('tr').forEach((tr) => {
                const q = parseFloat(tr.querySelector('.qte')?.value) || 0;
                const p = parseFloat(tr.querySelector('.pu')?.value) || 0;
                total += q * p;
            });
            grandTotalEl.textContent = fmt(total) + ' DH';
            return total;
        }

        function collectLines() {
            const lines = [];
            const catalog = getCatalogue();
            const byId = {};
            catalog.forEach(function (p) { byId[p.id] = p; });

            linesBody.querySelectorAll('tr').forEach((tr) => {
                const sel = tr.querySelector('.prod');
                const produitId = sel?.value?.trim();
                if (!produitId) return;
                const p = byId[produitId];
                if (!p) return;
                const qte = parseFloat(tr.querySelector('.qte')?.value) || 0;
                const pu = parseFloat(tr.querySelector('.pu')?.value) || 0;
                lines.push({
                    produitId: p.id,
                    ref: p.ref || '',
                    codeBarre: String(p.codeBarre || '').toUpperCase(),
                    produit: p.designation || '',
                    designation: p.designation || '',
                    categorie: p.categorie || '',
                    qte, pu, sousTotal: qte * pu
                });
            });
            return lines;
        }

        function linesAreValidFromStock() {
            const rows = [...linesBody.querySelectorAll('tr')];
            if (!rows.length) return false;
            const catalog = getCatalogue();
            if (!catalog.length) {
                alert('Catalogue stock vide. Ajoutez des articles dans Stock → Catégorie Produit.');
                return false;
            }
            for (let i = 0; i < rows.length; i++) {
                const sel = rows[i].querySelector('.prod');
                if (!sel || !sel.value) {
                    alert('Choisissez un article du catalogue stock pour chaque ligne.');
                    return false;
                }
            }
            return collectLines().length > 0;
        }

        function removeEmptyRow() {
            bonsBody.querySelector('.empty-row')?.remove();
        }

        function actionIcons() {
            return window.TableActions
                ? TableActions.iconsHtml(['view', 'edit', 'delete'])
                : '<div class="actions"><button type="button" class="icon-btn icon-delete" data-action="delete" title="Supprimer">×</button></div>';
        }

        function ensureBonsEmptyState() {
            if (!bonsBody.querySelector('tr[data-id]')) {
                bonsBody.innerHTML = '<tr class="empty-row"><td colspan="8" class="empty">Aucun bon de vente — cliquez sur Ajouter</td></tr>';
            }
        }

        function buildBonRow(bon) {
            const tr = document.createElement('tr');
            tr.setAttribute('data-id', bon.id || '');
            tr.innerHTML =
                '<td>' + (bon.date || '') + '</td>' +
                '<td>' + (bon.numero || '') + '</td>' +
                '<td>' + (bon.client || '') + '</td>' +
                '<td>' + (window.VenteStore ? VenteStore.fmtMoney(bon.montant) : fmt(bon.montant) + ' DH') + '</td>' +
                '<td>' + (bon.typePaie || '') + '</td>' +
                '<td>' + (window.VenteStore ? VenteStore.fmtMoney(bon.montantPaye) : fmt(bon.montantPaye) + ' DH') + '</td>' +
                '<td class="col-solde">' + (window.VenteStore ? VenteStore.fmtMoney(bon.solde) : fmt(bon.solde) + ' DH') + '</td>' +
                '<td class="actions-cell">' + actionIcons() + '</td>';
            return tr;
        }

        function renderBons() {
            if (!window.VenteStore) {
                ensureBonsEmptyState();
                return;
            }
            const list = VenteStore.getBons();
            if (!list.length) {
                bonsBody.innerHTML = '<tr class="empty-row"><td colspan="8" class="empty">Aucun bon de vente — cliquez sur Ajouter</td></tr>';
                return;
            }
            bonsBody.innerHTML = '';
            list.forEach(function (bon) {
                bonsBody.appendChild(buildBonRow(bon));
            });
        }

        function saveBon(typePaie, montantPaye) {
            const data = validateBonForm();
            if (!data) return false;

            const solde = Math.max(0, data.montant - montantPaye);
            const dateFr = data.date.split('-').reverse().join('/');

            const bon = {
                date: dateFr,
                numero: data.num,
                client: data.frns,
                montant: data.montant,
                typePaie: typePaie,
                montantPaye: montantPaye,
                solde: solde,
                lignes: data.lines || []
            };

            let saved = bon;
            if (window.VenteStore) {
                saved = VenteStore.addBon(bon);
            }

            removeEmptyRow();
            bonsBody.prepend(buildBonRow(saved));
            return true;
        }

        function initBonTableActions() {
            if (!window.TableActions) {
                console.error('TableActions non chargé');
                return;
            }
            TableActions.setHandlers({
                view: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var b = id && window.VenteStore ? VenteStore.getBon(id) : null;
                    if (!b) {
                        alert('Bon introuvable.');
                        return;
                    }
                    var lignes = (b.lignes || []).map(function (l) {
                        return '- ' + (l.designation || l.produit || '') + ' x' + (l.qte || 0);
                    }).join('\n') || '(aucune ligne)';
                    alert(
                        'Bon : ' + (b.numero || '') + '\n' +
                        'Date : ' + (b.date || '') + '\n' +
                        'Client : ' + (b.client || '') + '\n' +
                        'Montant : ' + (b.montant || 0) + '\n' +
                        'Payé : ' + (b.montantPaye || 0) + '\n' +
                        'Solde : ' + (b.solde || 0) + '\n\n' +
                        'Lignes :\n' + lignes
                    );
                },
                edit: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var b = id && window.VenteStore ? VenteStore.getBon(id) : null;
                    if (!b) {
                        alert('Bon introuvable.');
                        return;
                    }
                    openModal();
                    document.getElementById('dateBon').value = (function () {
                        var d = b.date || '';
                        if (d.indexOf('/') !== -1) {
                            var p = d.split('/');
                            return p[2] + '-' + p[1] + '-' + p[0];
                        }
                        return d;
                    })();
                    document.getElementById('numBon').value = b.numero || '';
                    document.getElementById('client').value = b.client || '';
                    linesBody.innerHTML = '';
                    var rows = b.lignes || [];
                    if (!rows.length) {
                        addLine();
                    } else {
                        rows.forEach(function (l) {
                            addLine();
                            var trLine = linesBody.lastElementChild;
                            if (!trLine) return;
                            var sel = trLine.querySelector('.prod');
                            if (sel && l.produitId) {
                                sel.value = l.produitId;
                                applyProductToLine(trLine);
                            }
                            var qte = trLine.querySelector('.qte');
                            var pu = trLine.querySelector('.pu');
                            if (qte) qte.value = l.qte || 1;
                            if (pu) pu.value = fmt(l.pu || l.prix || 0);
                            updateLineSub(trLine);
                        });
                    }
                    updateTotal();
                    setPayButtonsState(true);
                },
                delete: function (tr) {
                    var num = tr.cells[1] ? tr.cells[1].textContent.trim() : '';
                    var id = tr.getAttribute('data-id');
                    if (!confirm('Supprimer le bon ' + num + ' ?')) return;
                    if (id && window.VenteStore) VenteStore.deleteBon(id);
                    tr.parentNode.removeChild(tr);
                    ensureBonsEmptyState();
                }
            });
            TableActions.bind(bonsBody);
        }

        function refreshBonPage() {
            renderBons();
        }

        window.onCatalogueSynced = refreshBonPage;
        window.onVentesSynced = refreshBonPage;
        var bootBon = Promise.all([
            (window.StockStore && StockStore.initCatalogFromServer) ? StockStore.initCatalogFromServer() : Promise.resolve(),
            (window.VenteStore && VenteStore.initFromServer) ? VenteStore.initFromServer() : Promise.resolve()
        ]);
        bootBon.then(function () {
            renderBons();
            initBonTableActions();
        });

        document.getElementById('btnAjouter').addEventListener('click', openModal);
        document.getElementById('modalX').addEventListener('click', () => {
            closePayModal();
            closeModal();
        });

        btnValider.addEventListener('click', () => {
            // Valider → enregistre et affiche tout de suite (crédit)
            if (!saveBon('Crédit', 0)) return;
            closePayModal();
            closeModal();
        });

        btnPayer.addEventListener('click', openPayModal);

        btnFermerModal.addEventListener('click', () => {
            // Fermer = quitter sans enregistrer
            closePayModal();
            closeModal();
        });

        document.getElementById('payModalX').addEventListener('click', closePayModal);
        document.getElementById('btnPayFermer').addEventListener('click', closePayModal);

        function collectPayLignes() {
            const lignes = [];
            payLinesBody.querySelectorAll('tr').forEach((tr) => {
                const montant = parseFloat(tr.querySelector('.pay-montant')?.value) || 0;
                if (montant <= 0) return;
                lignes.push({
                    montant,
                    type: tr.querySelector('.pay-type')?.value || 'Esp',
                    statut: 'paye'
                });
            });
            return lignes;
        }

        document.getElementById('btnPayValider').addEventListener('click', () => {
            const { paid } = updatePaySoldes();
            const types = getPayTypes();
            const lignes = collectPayLignes();
            if (paid <= 0 || !lignes.length) {
                alert('Saisissez au moins un montant payé.');
                return;
            }
            const typeLabel = types.length ? types.join(' + ') : 'Esp';
            const data = validateBonForm();
            if (!data) return;

            if (window.VenteStore) {
                VenteStore.addReglementsFromPaiement({
                    date: data.date,
                    numBon: data.num,
                    client: data.frns,
                    lignes: lignes
                });
            }

            if (saveBon(typeLabel, paid)) {
                closePayModal();
                closeModal();
            }
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closePayModal();
                closeModal();
            }
        });
        modalPay.addEventListener('click', (e) => {
            if (e.target === modalPay) closePayModal();
        });

        setPayButtonsState(false);
    </script>
</body>
</html>
