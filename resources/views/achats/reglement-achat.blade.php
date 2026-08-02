<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Règlement Achat — 7ssabHani</title>
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
            background: rgba(139, 195, 74, 0.12);
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
            background: rgba(139, 195, 74, 0.16);
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

        .btn-add {
            color: var(--ink);
            background: linear-gradient(135deg, #a8d85a, var(--green-bright) 50%, #6fad35);
            box-shadow: 0 0 18px rgba(139, 195, 74, 0.35), 0 6px 14px rgba(0,0,0,0.12);
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
            border: 1px solid rgba(139, 195, 74, 0.14);
            overflow: hidden;
        }

        .table-scroll { overflow-x: auto; }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .data-table td {
            padding: 0.8rem 0.75rem;
            font-size: 0.88rem;
            border-bottom: 1px solid rgba(21, 32, 20, 0.06);
            color: var(--ink);
            vertical-align: middle;
            text-align: center;
        }

        .data-table tbody tr:hover { background: rgba(139, 195, 74, 0.06); }

        .data-table .empty {
            text-align: center;
            color: var(--muted);
            padding: 2.5rem 1rem;
        }

        .photo-thumb {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid rgba(21, 32, 20, 0.1);
            background: #eef3e8;
            display: inline-grid;
            place-items: center;
            color: var(--muted);
            font-size: 0.7rem;
            margin: 0 auto;
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
            border: 1px solid rgba(21, 32, 20, 0.1);
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
        .icon-btn.view:hover { background: rgba(61, 126, 166, 0.12); color: #3d7ea6; }
        .icon-btn.edit:hover { background: rgba(201, 162, 39, 0.15); color: #a8861a; }
        .icon-btn.delete:hover { background: rgba(184, 92, 56, 0.12); color: #b85c38; }
        .icon-btn.import:hover { background: rgba(47, 143, 107, 0.14); color: #2f8f6b; }
        .icon-btn.pdf:hover { background: rgba(183, 28, 28, 0.12); color: #b71c1c; }

        .status-select {
            appearance: none;
            -webkit-appearance: none;
            min-width: 120px;
            padding: 0.4rem 1.8rem 0.4rem 0.7rem;
            border-radius: 999px;
            border: none;
            font-family: inherit;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            text-align: center;
            cursor: pointer;
            outline: none;
            background-image:
                linear-gradient(45deg, transparent 50%, currentColor 50%),
                linear-gradient(135deg, currentColor 50%, transparent 50%);
            background-position:
                calc(100% - 14px) calc(50% - 2px),
                calc(100% - 9px) calc(50% - 2px);
            background-size: 5px 5px, 5px 5px;
            background-repeat: no-repeat;
            transition: box-shadow 0.15s, filter 0.15s;
        }
        .status-select:focus {
            box-shadow: 0 0 0 3px rgba(139, 195, 74, 0.25);
        }
        .status-select.status-paye { color: #1b5e20; background-color: rgba(76, 175, 80, 0.18); box-shadow: inset 0 0 0 1px rgba(76, 175, 80, 0.35); }
        .status-select.status-imp { color: #b71c1c; background-color: rgba(229, 57, 53, 0.15); box-shadow: inset 0 0 0 1px rgba(229, 57, 53, 0.35); }
        .status-select.status-attente { color: #546e7a; background-color: rgba(120, 144, 156, 0.18); box-shadow: inset 0 0 0 1px rgba(120, 144, 156, 0.35); }
        .status-select.status-reporte { color: #f57f17; background-color: rgba(255, 193, 7, 0.2); box-shadow: inset 0 0 0 1px rgba(255, 193, 7, 0.45); }
        .status-select.status-devalide { color: #6a1b9a; background-color: rgba(156, 39, 176, 0.15); box-shadow: inset 0 0 0 1px rgba(156, 39, 176, 0.35); }

        /* Modal règlement */
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
            width: min(100%, 820px);
            margin: 1.5rem auto;
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(16, 24, 14, 0.35), 0 0 0 1px rgba(139, 195, 74, 0.2);
            overflow: hidden;
        }
        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.1rem 1.35rem;
            background: linear-gradient(125deg, #1c2a18, #152014 60%, #243016);
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
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0.9rem;
            margin-bottom: 1rem;
        }
        .field label {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.72rem;
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
            border: 1px solid rgba(21, 32, 20, 0.12);
            background: #f7faf3;
            font-family: inherit;
            font-size: 0.92rem;
            color: var(--ink);
            outline: none;
            text-align: center;
        }
        .field input:focus,
        .field select:focus {
            border-color: rgba(139, 195, 74, 0.65);
            box-shadow: 0 0 0 3px rgba(139, 195, 74, 0.15);
            background: #fff;
        }
        .field input.readonly {
            background: #eef3e8;
            font-weight: 600;
            cursor: default;
        }
        .field.col-solde input {
            color: #c62828;
            font-weight: 700;
        }
        .import-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            margin: 0.5rem 0 1.1rem;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            background: #f3f6ef;
            border: 1px dashed rgba(139, 195, 74, 0.45);
        }
        .import-row input[type="file"] { display: none; }
        .photo-preview {
            width: 64px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid rgba(21,32,20,0.1);
            background: #e8eee2;
            display: none;
        }
        .photo-preview.show { display: block; }
        .modal-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.65rem;
            justify-content: flex-end;
        }
        .btn-validate {
            color: var(--ink);
            background: linear-gradient(135deg, #a8d85a, var(--green-bright));
            box-shadow: 0 0 16px rgba(139, 195, 74, 0.35);
        }
        .btn-import {
            color: #fff;
            background: linear-gradient(135deg, #3d7ea6, #2f6a8f);
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }

        @media (max-width: 900px) {
            .page-wrap { padding: 0 1rem 1.25rem; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="notranslate" translate="no">
    <div class="overlay" id="overlay"></div>

    <div class="app">
        @include('partials.sidebar', ['activePage' => 'reglement-achat', 'openMenu' => 'fournisseur'])

        <div class="main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div class="topbar-left">
                        <h1>Règlement Achat</h1>
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
                        <table class="data-table" id="reglementsTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>N° Rég</th>
                                    <th>Fournisseur</th>
                                    <th>Montant Rég</th>
                                    <th>Type</th>
                                    <th>Bnq</th>
                                    <th>Tiré</th>
                                    <th>Date Décaiss</th>
                                    <th>Photo</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="reglementsBody">
                                <tr class="empty-row">
                                    <td colspan="11" class="empty">Aucun règlement — les paiements saisis sur Bon Achat apparaîtront ici</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Fenêtre Ajouter Règlement --}}
    <div class="modal-backdrop" id="modalReg" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <h2>Nouveau <span>Règlement</span></h2>
                <button type="button" class="btn btn-close" id="regModalX" style="padding:0.45rem 0.7rem;">Fermer</button>
            </div>
            <div class="modal-body">
                <form id="formReg" autocomplete="off" onsubmit="return false;">
                    <div class="form-grid">
                        <div class="field">
                            <label for="regFournisseur">Fournisseur</label>
                            <select id="regFournisseur" required>
                                <option value="">Sélectionner un fournisseur</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="regBon">Bon N°</label>
                            <select id="regBon" required disabled>
                                <option value="">Sélectionner un bon</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="regMontantBon">Montant Bon</label>
                            <input type="text" id="regMontantBon" class="readonly" readonly tabindex="-1" value="0.00 DH">
                        </div>

                        <div class="field">
                            <label for="regMontantPaye">Montant Payé</label>
                            <input type="number" id="regMontantPaye" min="0" step="0.01" value="0" required>
                        </div>
                        <div class="field">
                            <label for="regType">Type</label>
                            <select id="regType">
                                <option value="Esp">Esp</option>
                                <option value="Chq">Chq</option>
                                <option value="Eff">Eff</option>
                                <option value="Vir">Vir</option>
                                <option value="En Compte">En Compte</option>
                                <option value="Crédit">Crédit</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="regBnq">Bnq</label>
                            <input type="text" id="regBnq" placeholder="Banque">
                        </div>

                        <div class="field">
                            <label for="regTire">Tiré</label>
                            <input type="text" id="regTire" placeholder="Tiré">
                        </div>
                        <div class="field">
                            <label for="regDateDecaiss">Date Décaiss</label>
                            <input type="date" id="regDateDecaiss">
                        </div>
                        <div class="field col-solde">
                            <label for="regSolde">Solde</label>
                            <input type="text" id="regSolde" class="readonly" readonly tabindex="-1" value="0.00 DH">
                        </div>
                    </div>

                    <div class="import-row">
                        <button type="button" class="btn btn-import" id="btnImporter">Importer</button>
                        <input type="file" id="regPhotoFile" accept="image/*">
                        <span id="regPhotoName" style="font-size:0.85rem;color:var(--muted)">Aucune photo</span>
                        <img id="regPhotoPreview" class="photo-preview" alt="Aperçu">
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-validate" id="btnRegValider">Valider</button>
                        <button type="button" class="btn btn-close" id="btnRegFermer">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table-actions.js') }}?v=5"></script>
    <script src="{{ asset('js/achat-store.js') }}?v=7"></script>
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

        const STATUS_OPTIONS = [
            { value: 'paye', label: 'Payé', className: 'status-paye' },
            { value: 'imp', label: 'Imp', className: 'status-imp' },
            { value: 'attente', label: 'En Attente', className: 'status-attente' },
            { value: 'reporte', label: 'Reporté', className: 'status-reporte' },
            { value: 'devalide', label: 'Dévalidé', className: 'status-devalide' },
        ];

        function buildStatusSelect(selected) {
            const options = STATUS_OPTIONS.map((s) =>
                '<option value="' + s.value + '"' + (s.value === selected ? ' selected' : '') + '>' + s.label + '</option>'
            ).join('');
            const current = STATUS_OPTIONS.find((s) => s.value === selected) || STATUS_OPTIONS[2];
            return '<select class="status-select ' + current.className + '" aria-label="Statut">' + options + '</select>';
        }

        function applyStatusClass(select) {
            STATUS_OPTIONS.forEach((s) => select.classList.remove(s.className));
            const found = STATUS_OPTIONS.find((s) => s.value === select.value);
            if (found) select.classList.add(found.className);
        }

        function bindStatusSelect(cell, id, status) {
            cell.setAttribute('data-status', status || 'paye');
            cell.innerHTML = buildStatusSelect(status || 'paye');
            const select = cell.querySelector('.status-select');
            if (!select) return;
            select.addEventListener('change', function () {
                applyStatusClass(select);
                if (window.AchatStore && id) {
                    AchatStore.updateReglementStatut(id, select.value);
                }
            });
        }

        function renderReglements() {
            const body = document.getElementById('reglementsBody');
            if (!body || !window.AchatStore) return;

            const list = AchatStore.getReglements();
            if (!list.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="11" class="empty">Aucun règlement — les paiements saisis sur Bon Achat apparaîtront ici</td></tr>';
                return;
            }

            body.innerHTML = list.map(function (r) {
                return '' +
                    '<tr data-id="' + r.id + '">' +
                    '<td>' + (r.date || '') + '</td>' +
                    '<td>' + (r.numero || '') + '</td>' +
                    '<td>' + (r.fournisseur || '') + '</td>' +
                    '<td>' + AchatStore.fmtMoney(r.montant) + '</td>' +
                    '<td>' + (r.type || '') + '</td>' +
                    '<td>' + (r.bnq || '—') + '</td>' +
                    '<td>' + (r.tire || '—') + '</td>' +
                    '<td>' + (r.dateDecaiss || '—') + '</td>' +
                    '<td><span class="photo-thumb">' +
                        (r.photo
                            ? '<img src="' + r.photo + '" alt="Photo" style="width:100%;height:100%;object-fit:cover;border-radius:8px;display:block">'
                            : '—') +
                    '</span></td>' +
                    '<td class="status-cell" data-status="' + (r.statut || 'paye') + '"></td>' +
                    '<td class="actions-cell"></td>' +
                    '</tr>';
            }).join('');

            if (window.TableActions) {
                TableActions.fillCells('#reglementsBody .actions-cell', ['view', 'edit', 'delete', 'import', 'pdf']);
            }

            body.querySelectorAll('tr[data-id]').forEach(function (tr) {
                const id = tr.getAttribute('data-id');
                const cell = tr.querySelector('.status-cell');
                const status = cell?.getAttribute('data-status') || 'paye';
                bindStatusSelect(cell, id, status);
            });
        }

        // Bind actions
        if (window.TableActions) {
            TableActions.setHandlers({
                view: function (tr) {
                    alert('Voir le règlement : ' + (tr.cells[1] ? tr.cells[1].textContent.trim() : ''));
                },
                edit: function (tr) {
                    alert('Modifier le règlement : ' + (tr.cells[1] ? tr.cells[1].textContent.trim() : ''));
                },
                delete: function (tr) {
                    var num = tr.cells[1] ? tr.cells[1].textContent.trim() : '';
                    var id = tr.getAttribute('data-id');
                    if (!confirm('Supprimer le règlement ' + num + ' ?')) return;
                    if (id && window.AchatStore) AchatStore.deleteReglement(id);
                    if (tr.parentNode) tr.parentNode.removeChild(tr);
                    var body = document.getElementById('reglementsBody');
                    if (body && !body.querySelector('tr[data-id]')) {
                        body.innerHTML = '<tr class="empty-row"><td colspan="11" class="empty">Aucun règlement — les paiements saisis sur Bon Achat apparaîtront ici</td></tr>';
                    }
                },
                import: function (tr) {
                    alert('Importer un fichier pour : ' + (tr.cells[1] ? tr.cells[1].textContent.trim() : ''));
                },
                pdf: function (tr) {
                    alert('Générer le PDF : ' + (tr.cells[1] ? tr.cells[1].textContent.trim() : ''));
                }
            });
            TableActions.bind(document.getElementById('reglementsBody'));
        }

        renderReglements();

        /* ——— Modal Ajouter Règlement ——— */
        const modalReg = document.getElementById('modalReg');
        const selFrns = document.getElementById('regFournisseur');
        const selBon = document.getElementById('regBon');
        const inpMontantBon = document.getElementById('regMontantBon');
        const inpMontantPaye = document.getElementById('regMontantPaye');
        const inpSolde = document.getElementById('regSolde');
        const inpDateDecaiss = document.getElementById('regDateDecaiss');
        const photoFile = document.getElementById('regPhotoFile');
        const photoPreview = document.getElementById('regPhotoPreview');
        const photoName = document.getElementById('regPhotoName');
        let selectedBon = null;
        let importedPhotoData = '';

        function openRegModal() {
            if (!window.AchatStore) return;
            const frns = AchatStore.getFournisseursNonSoldes();
            selFrns.innerHTML = '<option value="">Sélectionner un fournisseur</option>';
            frns.forEach(function (name) {
                selFrns.innerHTML += '<option value="' + name.replace(/"/g, '&quot;') + '">' + name + '</option>';
            });
            selBon.innerHTML = '<option value="">Sélectionner un bon</option>';
            selBon.disabled = true;
            selectedBon = null;
            importedPhotoData = '';
            inpMontantBon.value = '0.00 DH';
            inpMontantPaye.value = '0';
            inpSolde.value = '0.00 DH';
            document.getElementById('regBnq').value = '';
            document.getElementById('regTire').value = '';
            document.getElementById('regType').value = 'Esp';
            inpDateDecaiss.value = new Date().toISOString().slice(0, 10);
            photoFile.value = '';
            photoName.textContent = 'Aucune photo';
            photoPreview.classList.remove('show');
            photoPreview.removeAttribute('src');

            if (!frns.length) {
                alert('Aucun fournisseur avec bon non soldé. Créez d’abord un bon d’achat non soldé.');
                return;
            }

            modalReg.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeRegModal() {
            modalReg.classList.remove('show');
            document.body.style.overflow = '';
        }

        function updateRegSolde() {
            if (!selectedBon) {
                inpSolde.value = '0.00 DH';
                return;
            }
            var paye = parseFloat(inpMontantPaye.value) || 0;
            var soldeBon = Number(selectedBon.solde) || 0;
            var restant = Math.max(0, soldeBon - paye);
            inpSolde.value = AchatStore.fmtMoney(restant);
        }

        selFrns.addEventListener('change', function () {
            var name = selFrns.value;
            selectedBon = null;
            inpMontantBon.value = '0.00 DH';
            inpMontantPaye.value = '0';
            selBon.innerHTML = '<option value="">Sélectionner un bon</option>';
            if (!name) {
                selBon.disabled = true;
                updateRegSolde();
                return;
            }
            var bons = AchatStore.getBonsNonSoldesByFournisseur(name);
            bons.forEach(function (b) {
                selBon.innerHTML +=
                    '<option value="' + b.id + '">' +
                    b.numero + ' — solde ' + AchatStore.fmtMoney(b.solde) +
                    '</option>';
            });
            selBon.disabled = !bons.length;
            updateRegSolde();
        });

        selBon.addEventListener('change', function () {
            var id = selBon.value;
            var bons = AchatStore.getBonsNonSoldesByFournisseur(selFrns.value);
            selectedBon = null;
            for (var i = 0; i < bons.length; i++) {
                if (bons[i].id === id) { selectedBon = bons[i]; break; }
            }
            if (selectedBon) {
                inpMontantBon.value = AchatStore.fmtMoney(selectedBon.montant);
                inpMontantPaye.value = selectedBon.solde;
            } else {
                inpMontantBon.value = '0.00 DH';
                inpMontantPaye.value = '0';
            }
            updateRegSolde();
        });

        inpMontantPaye.addEventListener('input', updateRegSolde);

        document.getElementById('btnImporter').addEventListener('click', function () {
            photoFile.click();
        });

        photoFile.addEventListener('change', function () {
            var file = photoFile.files && photoFile.files[0];
            if (!file) return;
            if (!file.type || file.type.indexOf('image/') !== 0) {
                alert('Veuillez choisir une image.');
                return;
            }
            photoName.textContent = file.name;
            var reader = new FileReader();
            reader.onload = function (e) {
                importedPhotoData = e.target.result || '';
                photoPreview.src = importedPhotoData;
                photoPreview.classList.add('show');
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('btnAjouter').addEventListener('click', openRegModal);
        document.getElementById('regModalX').addEventListener('click', closeRegModal);
        document.getElementById('btnRegFermer').addEventListener('click', closeRegModal);
        modalReg.addEventListener('click', function (e) {
            if (e.target === modalReg) closeRegModal();
        });

        document.getElementById('btnRegValider').addEventListener('click', function () {
            if (!selectedBon) {
                alert('Sélectionnez un fournisseur et un bon non soldé.');
                return;
            }
            var paye = parseFloat(inpMontantPaye.value) || 0;
            if (paye <= 0) {
                alert('Saisissez un montant payé.');
                return;
            }
            if (paye > Number(selectedBon.solde) + 0.001) {
                alert('Le montant payé ne peut pas dépasser le solde du bon.');
                return;
            }

            var type = document.getElementById('regType').value;
            var bnq = document.getElementById('regBnq').value.trim() || '—';
            var tire = document.getElementById('regTire').value.trim() || '—';
            var dateDec = inpDateDecaiss.value
                ? AchatStore.formatDateFR(inpDateDecaiss.value)
                : AchatStore.formatDateFR();

            AchatStore.addReglement({
                fournisseur: selectedBon.fournisseur,
                montant: paye,
                type: type,
                bnq: bnq,
                tire: tire,
                dateDecaiss: dateDec,
                photo: importedPhotoData,
                statut: 'paye',
                numBon: selectedBon.numero,
                bonId: selectedBon.id
            });

            AchatStore.applyPaiementToBon(selectedBon.id, paye);
            renderReglements();
            closeRegModal();
        });
    </script>
</body>
</html>
