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
            transition: transform 0.15s, filter 0.15s, box-shadow 0.15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn svg { width: 16px; height: 16px; }
        .btn-add {
            color: var(--ink);
            background: linear-gradient(135deg, #ffb83a, var(--green-bright) 50%, #e8920a);
            box-shadow: 0 0 18px rgba(252,163,17,0.35), 0 6px 14px rgba(0,0,0,0.12);
        }
        .btn-close { color: #fff; background: linear-gradient(135deg, #5a6570, #3d4650); box-shadow: 0 6px 14px rgba(0,0,0,0.12); }
        .btn-validate {
            color: var(--ink);
            background: linear-gradient(135deg, #ffb83a, var(--green-bright));
            box-shadow: 0 0 16px rgba(252,163,17,0.35);
        }

        .table-card {
            background: var(--white); border-radius: 18px; box-shadow: var(--shadow-card);
            border: 1px solid rgba(252,163,17,0.14); overflow: hidden;
        }
        .table-scroll { overflow-x: auto; }
        table.data-table { width: 100%; border-collapse: collapse; min-width: 1100px; }
        .data-table th {
            padding: 0.85rem 0.7rem; font-family: 'Outfit', sans-serif; font-size: 0.78rem;
            font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;
            color: var(--muted); background: #f4f7fb; border-bottom: 1px solid rgba(13,27,42,0.08);
            text-align: center; white-space: nowrap;
        }
        .data-table td {
            padding: 0.8rem 0.7rem; font-size: 0.88rem;
            border-bottom: 1px solid rgba(13,27,42,0.06); color: var(--ink);
            vertical-align: middle; text-align: center;
        }
        .data-table tbody tr:hover { background: rgba(252,163,17,0.06); }
        .data-table .empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; }
        .desig-cell { font-weight: 600; text-align: left !important; padding-left: 1rem !important; }
        .money { font-variant-numeric: tabular-nums; font-weight: 600; }
        .ref-badge {
            display: inline-block; padding: 0.28rem 0.7rem; border-radius: 999px;
            font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 0.78rem;
            background: rgba(252,163,17,0.15); color: var(--green-deep);
            border: 1px solid rgba(252,163,17,0.3);
        }
        .photo-thumb {
            width: 42px; height: 42px; border-radius: 10px; object-fit: cover;
            border: 1px solid rgba(13,27,42,0.1); background: #eef2f7;
            display: inline-grid; place-items: center; color: var(--muted);
            font-size: 0.65rem; margin: 0 auto; overflow: hidden;
        }
        .photo-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .btn-import {
            color: #fff;
            background: linear-gradient(135deg, #3d7ea6, #2f6284);
            box-shadow: 0 6px 14px rgba(47,98,132,0.25);
        }
        .import-row {
            display: flex; flex-wrap: wrap; align-items: center; gap: 0.75rem;
            margin-bottom: 1rem; padding: 0.85rem 1rem;
            border: 1px dashed rgba(13,27,42,0.15); border-radius: 12px; background: #f8fafc;
        }
        .import-row input[type="file"] { display: none; }
        .photo-preview {
            display: none; width: 72px; height: 72px; object-fit: cover;
            border-radius: 10px; border: 1px solid rgba(13,27,42,0.12);
        }
        .photo-preview.show { display: block; }

        .actions { display: flex; gap: 0.35rem; justify-content: center; flex-wrap: wrap; position: relative; z-index: 2; }
        .icon-btn {
            width: 32px; height: 32px; border-radius: 9px;
            border: 1px solid rgba(13,27,42,0.1); background: #f4f7fb; color: var(--ink-soft);
            display: inline-grid; place-items: center; cursor: pointer;
            pointer-events: auto !important; position: relative; z-index: 10;
            transition: background 0.15s, color 0.15s, box-shadow 0.15s;
        }
        .icon-btn svg, .icon-btn svg * {
            width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 1.8;
            pointer-events: none !important;
        }
        .icon-btn:hover { box-shadow: 0 4px 12px rgba(13,27,42,0.1); }
        .icon-btn.icon-view:hover { background: rgba(61,126,166,0.12); color: #3d7ea6; }
        .icon-btn.icon-edit:hover { background: rgba(252,163,17,0.15); color: #c47e00; }
        .icon-btn.icon-delete:hover { background: rgba(184,92,56,0.12); color: #b85c38; }

        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(7,17,28,0.55); backdrop-filter: blur(4px);
            z-index: 80; display: none; pointer-events: none;
            align-items: flex-start; justify-content: center; padding: 1.25rem; overflow-y: auto;
        }
        .modal-backdrop.show { display: flex; pointer-events: auto; }
        .modal {
            width: min(100%, 760px); margin: 1.5rem auto; background: var(--white);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(7,17,28,0.35), 0 0 0 1px rgba(252,163,17,0.2);
            overflow: hidden;
        }
        .modal-head {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            padding: 1.1rem 1.35rem;
            background: linear-gradient(125deg, #14213d, #0d1b2a 60%, #07111c); color: #fff;
        }
        .modal-head h2 { font-family: 'Outfit', sans-serif; font-size: 1.2rem; font-weight: 700; }
        .modal-head h2 span { color: var(--gold); }
        .modal-body { padding: 1.25rem 1.35rem 1.4rem; }
        .form-grid {
            display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.9rem; margin-bottom: 1rem;
        }
        .field.full { grid-column: 1 / -1; }
        .field label {
            display: block; margin-bottom: 0.35rem; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase; color: var(--muted);
        }
        .field input, .field select {
            width: 100%; padding: 0.7rem 0.85rem; border-radius: 10px;
            border: 1px solid rgba(13,27,42,0.12); background: #f4f7fb;
            font-family: inherit; font-size: 0.92rem; color: var(--ink); outline: none; text-align: center;
        }
        .field input:focus, .field select:focus {
            border-color: rgba(252,163,17,0.65); box-shadow: 0 0 0 3px rgba(252,163,17,0.15); background: #fff;
        }
        .field input.readonly { background: #eef2f7; font-weight: 600; cursor: default; }
        #produitCodeBarre,
        .code-barre-cell { text-transform: uppercase; letter-spacing: 0.04em; }
        .field input:disabled, .field select:disabled {
            opacity: 0.85; cursor: default; background: #eef2f7;
        }
        .modal-actions { display: flex; flex-wrap: wrap; gap: 0.65rem; justify-content: flex-end; }

        @media (max-width: 700px) {
            .form-grid { grid-template-columns: 1fr; }
            .page-wrap { padding: 0 1rem 1.25rem; }
        }
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
                        <table class="data-table" id="catTable">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Réf</th>
                                    <th>Code Barre</th>
                                    <th>Désignation</th>
                                    <th>Catégorie</th>
                                    <th>Famille</th>
                                    <th>Quantité</th>
                                    <th>P/A</th>
                                    <th>P/V</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="catBody">
                                <tr class="empty-row">
                                    <td colspan="10" class="empty">Aucun produit — cliquez sur Ajouter</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-backdrop" id="modalProduit" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <h2 id="modalProduitTitle">Nouveau <span>Produit</span></h2>
                <button type="button" class="btn btn-close" id="produitModalX" style="padding:0.45rem 0.7rem;">Fermer</button>
            </div>
            <div class="modal-body">
                <form id="formProduit" autocomplete="off" onsubmit="return false;">
                    <input type="hidden" id="produitEditId" value="">
                    <div class="form-grid">
                        <div class="field">
                            <label for="produitRef">Réf</label>
                            <input type="text" id="produitRef" name="ref" required>
                        </div>
                        <div class="field">
                            <label for="produitCodeBarre">Code Barre</label>
                            <input type="text" id="produitCodeBarre" name="codeBarre" style="text-transform:uppercase" autocomplete="off" spellcheck="false">
                        </div>
                        <div class="field">
                            <label for="produitDesignation">Désignation</label>
                            <input type="text" id="produitDesignation" name="designation" required>
                        </div>
                        <div class="field">
                            <label for="produitCategorie">Catégorie</label>
                            <input type="text" id="produitCategorie" name="categorie">
                        </div>
                        <div class="field">
                            <label for="produitFamille">Famille</label>
                            <input type="text" id="produitFamille" name="famille">
                        </div>
                        <div class="field">
                            <label for="produitQuantite">Quantité</label>
                            <input type="number" id="produitQuantite" name="quantite" min="0" step="1" value="0">
                        </div>
                        <div class="field">
                            <label for="produitPa">P/A</label>
                            <input type="number" id="produitPa" name="pa" min="0" step="0.01" value="0">
                        </div>
                        <div class="field">
                            <label for="produitPv">P/V</label>
                            <input type="number" id="produitPv" name="pv" min="0" step="0.01" value="0">
                        </div>
                    </div>

                    <div class="import-row">
                        <button type="button" class="btn btn-import" id="btnImporterPhoto">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>
                            Importer photo
                        </button>
                        <input type="file" id="produitPhotoFile" accept="image/*">
                        <span id="produitPhotoName" style="font-size:0.85rem;color:var(--muted)">Aucune photo</span>
                        <img id="produitPhotoPreview" class="photo-preview" alt="Aperçu">
                        <button type="button" class="btn btn-close" id="btnPhotoClear" style="padding:0.45rem 0.75rem;display:none;">Retirer</button>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-validate" id="btnProduitValider">Valider</button>
                        <button type="button" class="btn btn-close" id="btnProduitFermer">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/data-sync.js') }}?v=1"></script>
    <script src="{{ asset('js/table-actions.js') }}?v=7"></script>
    <script src="{{ asset('js/stock-store.js') }}?v=9"></script>
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

        const modalProduit = document.getElementById('modalProduit');
        const modalTitle = document.getElementById('modalProduitTitle');
        const fields = ['produitRef', 'produitCodeBarre', 'produitDesignation', 'produitCategorie', 'produitFamille', 'produitQuantite', 'produitPa', 'produitPv'];
        const photoFile = document.getElementById('produitPhotoFile');
        const photoPreview = document.getElementById('produitPhotoPreview');
        const photoName = document.getElementById('produitPhotoName');
        const btnPhotoClear = document.getElementById('btnPhotoClear');
        const btnImporterPhoto = document.getElementById('btnImporterPhoto');
        let editMode = false;
        let viewMode = false;
        let importedPhotoData = '';

        function money(n) {
            return window.StockStore ? StockStore.fmtMoney(n) : Number(n || 0).toFixed(2);
        }

        function resetPhotoUI() {
            importedPhotoData = '';
            photoFile.value = '';
            photoName.textContent = 'Aucune photo';
            photoPreview.classList.remove('show');
            photoPreview.removeAttribute('src');
            btnPhotoClear.style.display = 'none';
        }

        function setPhotoUI(dataUrl, label) {
            importedPhotoData = dataUrl || '';
            if (importedPhotoData) {
                photoPreview.src = importedPhotoData;
                photoPreview.classList.add('show');
                photoName.textContent = label || 'Photo importée';
                btnPhotoClear.style.display = viewMode ? 'none' : '';
            } else {
                resetPhotoUI();
            }
        }

        function setFieldsReadonly(ro) {
            fields.forEach(function (id) {
                var el = document.getElementById(id);
                if (!el) return;
                el.readOnly = !!ro;
                el.classList.toggle('readonly', !!ro);
                el.disabled = false;
            });
            document.getElementById('btnProduitValider').style.display = ro ? 'none' : '';
            btnImporterPhoto.style.display = ro ? 'none' : '';
            btnPhotoClear.style.display = (ro || !importedPhotoData) ? 'none' : '';
            photoFile.disabled = !!ro;
        }

        function photoCell(p) {
            if (p.photo) {
                return '<span class="photo-thumb"><img src="' + p.photo + '" alt=""></span>';
            }
            return '<span class="photo-thumb">—</span>';
        }

        function renderCatalogue() {
            var body = document.getElementById('catBody');
            if (!body || !window.StockStore) return;
            var list = StockStore.getCatalogue();
            if (!list.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="10" class="empty">Aucun produit — cliquez sur Ajouter</td></tr>';
                return;
            }
            body.innerHTML = list.map(function (p) {
                return '' +
                    '<tr data-id="' + p.id + '">' +
                    '<td>' + photoCell(p) + '</td>' +
                    '<td><span class="ref-badge">' + (p.ref || '—') + '</span></td>' +
                    '<td class="code-barre-cell">' + (p.codeBarre ? String(p.codeBarre).toUpperCase() : '—') + '</td>' +
                    '<td class="desig-cell">' + (p.designation || '') + '</td>' +
                    '<td>' + (p.categorie || '—') + '</td>' +
                    '<td>' + (p.famille || '—') + '</td>' +
                    '<td>' + (Number(p.quantite) || 0) + '</td>' +
                    '<td class="money">' + money(p.pa) + '</td>' +
                    '<td class="money">' + money(p.pv) + '</td>' +
                    '<td class="actions-cell"></td>' +
                    '</tr>';
            }).join('');

            if (window.TableActions) {
                TableActions.fillCells('#catBody .actions-cell', ['view', 'edit', 'delete']);
            }
        }

        function openModal(produit, mode) {
            editMode = mode === 'edit';
            viewMode = mode === 'view';
            document.getElementById('formProduit').reset();
            document.getElementById('produitEditId').value = produit && produit.id ? produit.id : '';
            resetPhotoUI();

            if (viewMode) {
                modalTitle.innerHTML = 'Voir <span>Produit</span>';
            } else if (editMode) {
                modalTitle.innerHTML = 'Modifier <span>Produit</span>';
            } else {
                modalTitle.innerHTML = 'Nouveau <span>Produit</span>';
            }

            document.getElementById('produitRef').value = produit && produit.ref
                ? produit.ref
                : (window.StockStore ? StockStore.nextCatalogRef() : 'PR-0001');
            document.getElementById('produitCodeBarre').value = produit ? String(produit.codeBarre || '').toUpperCase() : '';
            document.getElementById('produitDesignation').value = produit ? (produit.designation || '') : '';
            document.getElementById('produitCategorie').value = produit ? (produit.categorie || '') : '';
            document.getElementById('produitFamille').value = produit ? (produit.famille || '') : '';
            document.getElementById('produitQuantite').value = produit ? (Number(produit.quantite) || 0) : 0;
            document.getElementById('produitPa').value = produit ? money(produit.pa) : '0.00';
            document.getElementById('produitPv').value = produit ? money(produit.pv) : '0.00';

            if (produit && produit.photo) {
                setPhotoUI(produit.photo, 'Photo enregistrée');
            }

            setFieldsReadonly(viewMode);
            modalProduit.classList.add('show');
            document.body.style.overflow = 'hidden';
            if (!viewMode) {
                document.getElementById('produitRef').focus();
            }
        }

        function closeModal() {
            modalProduit.classList.remove('show');
            document.body.style.overflow = '';
            editMode = false;
            viewMode = false;
            resetPhotoUI();
            setFieldsReadonly(false);
        }

        document.getElementById('btnAjouter').addEventListener('click', function () { openModal(null, 'add'); });
        document.getElementById('produitModalX').addEventListener('click', closeModal);
        document.getElementById('btnProduitFermer').addEventListener('click', closeModal);
        modalProduit.addEventListener('click', function (e) { if (e.target === modalProduit) closeModal(); });

        btnImporterPhoto.addEventListener('click', function () {
            if (viewMode) return;
            photoFile.click();
        });

        btnPhotoClear.addEventListener('click', function () {
            if (viewMode) return;
            resetPhotoUI();
        });

        photoFile.addEventListener('change', function () {
            var file = photoFile.files && photoFile.files[0];
            if (!file) return;
            if (!file.type || file.type.indexOf('image/') !== 0) {
                alert('Veuillez choisir une image.');
                photoFile.value = '';
                return;
            }
            if (file.size > 1.5 * 1024 * 1024) {
                alert('Image trop lourde (max 1,5 Mo).');
                photoFile.value = '';
                return;
            }
            var reader = new FileReader();
            reader.onload = function () {
                setPhotoUI(String(reader.result || ''), file.name);
                setFieldsReadonly(viewMode);
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('btnProduitValider').addEventListener('click', function () {
            if (viewMode || !window.StockStore) return;
            var ref = document.getElementById('produitRef').value.trim();
            var designation = document.getElementById('produitDesignation').value.trim();
            if (!ref) {
                alert('Veuillez renseigner la Réf.');
                document.getElementById('produitRef').focus();
                return;
            }
            if (!designation) {
                alert('Veuillez renseigner la Désignation.');
                document.getElementById('produitDesignation').focus();
                return;
            }
            var saved = StockStore.saveProduit({
                id: document.getElementById('produitEditId').value || '',
                ref: ref,
                codeBarre: document.getElementById('produitCodeBarre').value,
                designation: designation,
                categorie: document.getElementById('produitCategorie').value,
                famille: document.getElementById('produitFamille').value,
                quantite: document.getElementById('produitQuantite').value,
                pa: document.getElementById('produitPa').value,
                pv: document.getElementById('produitPv').value,
                photo: importedPhotoData
            });
            if (!saved) {
                alert('Enregistrement impossible.');
                return;
            }
            closeModal();
            renderCatalogue();
        });

        if (window.TableActions) {
            TableActions.setHandlers({
                view: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var p = StockStore.getProduit(id);
                    if (p) openModal(p, 'view');
                },
                edit: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var p = StockStore.getProduit(id);
                    if (p) openModal(p, 'edit');
                },
                delete: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var p = StockStore.getProduit(id);
                    var label = p ? (p.designation || 'ce produit') : 'ce produit';
                    if (!confirm('Supprimer ' + label + ' ?')) return;
                    StockStore.deleteProduit(id);
                    renderCatalogue();
                }
            });
        }

        window.onCatalogueSynced = renderCatalogue;
        var bootCatalogue = window.StockStore && StockStore.initCatalogFromServer
            ? StockStore.initCatalogFromServer()
            : Promise.resolve();
        bootCatalogue.then(renderCatalogue);

        (function () {
            var cb = document.getElementById('produitCodeBarre');
            if (!cb) return;
            cb.addEventListener('input', function () {
                var pos = cb.selectionStart;
                cb.value = cb.value.toUpperCase();
                if (typeof pos === 'number') cb.setSelectionRange(pos, pos);
            });
        })();
    </script>
</body>
</html>
