<!DOCTYPE html>
<html lang="fr" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google" content="notranslate">
    <title>Utilisateurs — 7ssabHani</title>
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
            transition: transform 0.15s, filter 0.15s, box-shadow 0.15s;
        }
        .btn:hover { transform: translateY(-1px); }
        .btn svg { width: 16px; height: 16px; }
        .btn-add {
            color: var(--ink);
            background: linear-gradient(135deg, #a8d85a, var(--green-bright) 50%, #6fad35);
            box-shadow: 0 0 18px rgba(139,195,74,0.35), 0 6px 14px rgba(0,0,0,0.12);
        }
        .btn-close { color: #fff; background: linear-gradient(135deg, #5a6570, #3d4650); box-shadow: 0 6px 14px rgba(0,0,0,0.12); }
        .btn-validate {
            color: var(--ink);
            background: linear-gradient(135deg, #a8d85a, var(--green-bright));
            box-shadow: 0 0 16px rgba(139,195,74,0.35);
        }

        .table-card {
            background: var(--white); border-radius: 18px; box-shadow: var(--shadow-card);
            border: 1px solid rgba(139,195,74,0.14); overflow: hidden;
        }
        .table-scroll { overflow-x: auto; }
        table.data-table { width: 100%; border-collapse: collapse; min-width: 980px; }
        .data-table td {
            padding: 0.8rem 0.75rem; font-size: 0.88rem;
            border-bottom: 1px solid rgba(21,32,20,0.06); color: var(--ink);
            vertical-align: middle; text-align: center;
        }
        .data-table tbody tr:hover { background: rgba(139,195,74,0.06); }
        .data-table .empty { text-align: center; color: var(--muted); padding: 2.5rem 1rem; }
        .nom-cell { font-weight: 600; text-align: left !important; padding-left: 1rem !important; }

        .actions { display: flex; gap: 0.35rem; justify-content: center; flex-wrap: wrap; position: relative; z-index: 2; }
        .icon-btn {
            width: 32px; height: 32px; border-radius: 9px;
            border: 1px solid rgba(21,32,20,0.1); background: #f4f7f0; color: var(--ink-soft);
            display: inline-grid; place-items: center; cursor: pointer;
            pointer-events: auto !important; position: relative; z-index: 10;
            transition: background 0.15s, color 0.15s, box-shadow 0.15s;
        }
        .icon-btn svg, .icon-btn svg * {
            width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 1.8;
            pointer-events: none !important;
        }
        .icon-btn:hover { box-shadow: 0 4px 12px rgba(21,32,20,0.1); }
        .icon-btn.icon-view:hover { background: rgba(61,126,166,0.12); color: #3d7ea6; }
        .icon-btn.icon-edit:hover { background: rgba(201,162,39,0.15); color: #a8861a; }
        .icon-btn.icon-delete:hover { background: rgba(184,92,56,0.12); color: #b85c38; }
        .icon-btn.icon-suspend:hover { background: rgba(245,127,23,0.14); color: #e65100; }

        .statut-badge {
            display: inline-block; min-width: 96px; padding: 0.35rem 0.7rem; border-radius: 999px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.03em; text-transform: uppercase;
        }
        .statut-actif { color: #1b5e20; background: rgba(76,175,80,0.18); box-shadow: inset 0 0 0 1px rgba(76,175,80,0.35); }
        .statut-suspendu { color: #e65100; background: rgba(255,152,0,0.18); box-shadow: inset 0 0 0 1px rgba(255,152,0,0.4); }
        .statut-inactif { color: #546e7a; background: rgba(120,144,156,0.18); box-shadow: inset 0 0 0 1px rgba(120,144,156,0.35); }

        .pwd-mask { font-family: monospace; letter-spacing: 0.08em; color: var(--muted); }

        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(10,16,8,0.55); backdrop-filter: blur(4px);
            z-index: 80; display: none; pointer-events: none;
            align-items: flex-start; justify-content: center; padding: 1.25rem; overflow-y: auto;
        }
        .modal-backdrop.show { display: flex; pointer-events: auto; }
        .modal {
            width: min(100%, 720px); margin: 1.5rem auto; background: var(--white);
            border-radius: 20px;
            box-shadow: 0 24px 60px rgba(16,24,14,0.35), 0 0 0 1px rgba(139,195,74,0.2);
            overflow: hidden;
        }
        .modal-head {
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
            padding: 1.1rem 1.35rem;
            background: linear-gradient(125deg, #1c2a18, #152014 60%, #243016); color: #fff;
        }
        .modal-head h2 { font-family: 'Outfit', sans-serif; font-size: 1.2rem; font-weight: 700; }
        .modal-head h2 span { color: var(--gold); }
        .modal-body { padding: 1.25rem 1.35rem 1.4rem; }
        .form-grid {
            display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.9rem; margin-bottom: 1rem;
        }
        .field label {
            display: block; margin-bottom: 0.35rem; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.03em; text-transform: uppercase; color: var(--muted);
        }
        .field input, .field select {
            width: 100%; padding: 0.7rem 0.85rem; border-radius: 10px;
            border: 1px solid rgba(21,32,20,0.12); background: #f7faf3;
            font-family: inherit; font-size: 0.92rem; color: var(--ink); outline: none; text-align: center;
        }
        .field input:focus, .field select:focus {
            border-color: rgba(139,195,74,0.65); box-shadow: 0 0 0 3px rgba(139,195,74,0.15); background: #fff;
        }
        .field input.readonly { background: #eef3e8; font-weight: 600; cursor: default; }
        .login-wrap { display: flex; align-items: stretch; gap: 0; }
        .login-wrap input { border-radius: 10px 0 0 10px; text-align: left; flex: 1; min-width: 0; }
        .login-suffix {
            display: inline-flex; align-items: center; padding: 0 0.75rem;
            border: 1px solid rgba(21,32,20,0.12); border-left: none; border-radius: 0 10px 10px 0;
            background: #e8eee2; color: var(--ink-soft); font-size: 0.82rem; font-weight: 600; white-space: nowrap;
        }
        .password-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrap input {
            width: 100%;
            padding-right: 2.75rem;
            text-align: left;
        }
        .toggle-password {
            position: absolute;
            right: 0.4rem;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            display: grid;
            place-items: center;
            padding: 0;
        }
        .toggle-password:hover { color: var(--ink); background: rgba(21,32,20,0.06); }
        .toggle-password svg {
            width: 17px; height: 17px; stroke: currentColor; fill: none; stroke-width: 1.8;
        }
        .toggle-password .icon-hide { display: none; }
        .toggle-password.is-visible .icon-show { display: none; }
        .toggle-password.is-visible .icon-hide { display: block; }
        .hint-field { margin-top: 0.3rem; font-size: 0.75rem; color: var(--muted); text-align: left; }
        .modal-actions { display: flex; flex-wrap: wrap; gap: 0.65rem; justify-content: flex-end; }

        @media (max-width: 900px) {
            .page-wrap { padding: 0 1rem 1.25rem; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="notranslate" translate="no">
    <div class="overlay" id="overlay"></div>
    <div class="app">
        @include('partials.sidebar', ['activePage' => 'utilisateurs', 'openMenu' => 'configuration'])

        <div class="main">
            <header class="topbar">
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Ouvrir le menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"/></svg>
                    </button>
                    <div class="topbar-left">
                        <h1>Utilisateurs</h1>
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
                        <table class="data-table" id="usersTable">
                            <thead>
                                <tr>
                                    <th>Nom Complet</th>
                                    <th>Statut</th>
                                    <th>Contact</th>
                                    <th>Login</th>
                                    <th>Mot de Passe</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="usersBody">
                                <tr class="empty-row">
                                    <td colspan="6" class="empty">Aucun utilisateur — cliquez sur Ajouter</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-backdrop" id="modalUser" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-head">
                <h2 id="modalUserTitle">Nouvel <span>Utilisateur</span></h2>
                <button type="button" class="btn btn-close" id="userModalX" style="padding:0.45rem 0.7rem;">Fermer</button>
            </div>
            <div class="modal-body">
                <form id="formUser" autocomplete="off" onsubmit="return false;">
                    <input type="hidden" id="userEditId" value="">
                    <input type="text" name="fakeuser" style="display:none" tabindex="-1" autocomplete="username">
                    <input type="password" name="fakepass" style="display:none" tabindex="-1" autocomplete="current-password">
                    <div class="form-grid">
                        <div class="field">
                            <label for="userDate">Date</label>
                            <input type="date" id="userDate" required>
                        </div>
                        <div class="field">
                            <label for="userId">ID</label>
                            <input type="text" id="userId" class="readonly" readonly tabindex="-1">
                        </div>
                        <div class="field">
                            <label for="userNom">Nom Complet</label>
                            <input type="text" id="userNom" required autocomplete="off">
                        </div>
                        <div class="field">
                            <label for="userStatut">Statut</label>
                            <select id="userStatut" required autocomplete="off">
                                <option value=""></option>
                                <option value="Actif">Actif</option>
                                <option value="Suspendu">Suspendu</option>
                                <option value="Inactif">Inactif</option>
                            </select>
                        </div>
                        <div class="field">
                            <label for="userContact">Contact</label>
                            <input type="text" id="userContact" autocomplete="off">
                        </div>
                        <div class="field">
                            <label for="userLogin">Login</label>
                            <input type="text" id="userLogin" name="user_login_field" required autocomplete="off">
                        </div>
                        <div class="field" style="grid-column: 1 / -1;">
                            <label for="userPassword">Mot de Passe</label>
                            <div class="password-wrap">
                                <input type="password" id="userPassword" name="user_password_field" minlength="8" required autocomplete="new-password">
                                <button type="button" class="toggle-password" id="toggleUserPassword" aria-label="Afficher le mot de passe" title="Afficher / masquer">
                                    <svg class="icon-show" viewBox="0 0 24 24"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg class="icon-hide" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 19c-6 0-10-7-10-7a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c6 0 10 7 10 7a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><path d="M1 1l22 22"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-validate" id="btnUserValider">Valider</button>
                        <button type="button" class="btn btn-close" id="btnUserFermer">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/table-actions.js') }}?v=6"></script>
    <script src="{{ asset('js/users-store.js') }}?v=1"></script>
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

        const modalUser = document.getElementById('modalUser');
        const modalTitle = document.getElementById('modalUserTitle');
        let editMode = false;

        function statutClass(s) {
            var v = String(s || '').toLowerCase();
            if (v === 'suspendu') return 'statut-suspendu';
            if (v === 'inactif') return 'statut-inactif';
            return 'statut-actif';
        }

        function maskPwd(pwd) {
            var n = Math.max(8, String(pwd || '').length);
            return '•'.repeat(Math.min(n, 12));
        }

        function toIsoDate(fr) {
            if (!fr) return new Date().toISOString().slice(0, 10);
            if (fr.indexOf('-') !== -1) return fr;
            var p = fr.split('/');
            if (p.length === 3) return p[2] + '-' + p[1] + '-' + p[0];
            return new Date().toISOString().slice(0, 10);
        }

        function renderUsers() {
            var body = document.getElementById('usersBody');
            if (!body || !window.UsersStore) return;
            var list = UsersStore.getUsers();
            if (!list.length) {
                body.innerHTML = '<tr class="empty-row"><td colspan="6" class="empty">Aucun utilisateur — cliquez sur Ajouter</td></tr>';
                return;
            }
            body.innerHTML = list.map(function (u) {
                return '' +
                    '<tr data-id="' + u.id + '">' +
                    '<td class="nom-cell">' + (u.nomComplet || '') + '</td>' +
                    '<td><span class="statut-badge ' + statutClass(u.statut) + '">' + (u.statut || 'Actif') + '</span></td>' +
                    '<td>' + (u.contact || '—') + '</td>' +
                    '<td>' + (u.login || '') + '</td>' +
                    '<td><span class="pwd-mask" title="Mot de passe masqué">' + maskPwd(u.password) + '</span></td>' +
                    '<td class="actions-cell"></td>' +
                    '</tr>';
            }).join('');

            if (window.TableActions) {
                TableActions.fillCells('#usersBody .actions-cell', ['view', 'edit', 'delete', 'suspend']);
            }
        }

        function openModal(user) {
            editMode = !!(user && user.id);
            document.getElementById('userEditId').value = editMode ? user.id : '';
            modalTitle.innerHTML = editMode ? 'Modifier <span>Utilisateur</span>' : 'Nouvel <span>Utilisateur</span>';

            document.getElementById('formUser').reset();
            document.getElementById('userEditId').value = editMode ? user.id : '';

            document.getElementById('userDate').value = editMode ? toIsoDate(user.date) : new Date().toISOString().slice(0, 10);
            document.getElementById('userId').value = editMode ? (user.idCode || '') : UsersStore.nextId();
            document.getElementById('userNom').value = editMode ? (user.nomComplet || '') : '';
            document.getElementById('userContact').value = editMode ? (user.contact || '') : '';

            // Statut, Login, Mot de passe : toujours vides — saisie obligatoire
            document.getElementById('userStatut').value = '';
            document.getElementById('userStatut').selectedIndex = 0;
            document.getElementById('userLogin').value = '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = true;

            modalUser.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modalUser.classList.remove('show');
            document.body.style.overflow = '';
            editMode = false;
        }

        document.getElementById('btnAjouter').addEventListener('click', function () { openModal(null); });
        document.getElementById('userModalX').addEventListener('click', closeModal);
        document.getElementById('btnUserFermer').addEventListener('click', closeModal);
        modalUser.addEventListener('click', function (e) { if (e.target === modalUser) closeModal(); });

        document.getElementById('btnUserValider').addEventListener('click', function () {
            try {
                var payload = {
                    date: UsersStore.formatDateFR(document.getElementById('userDate').value),
                    idCode: document.getElementById('userId').value.trim(),
                    nomComplet: document.getElementById('userNom').value.trim(),
                    statut: document.getElementById('userStatut').value,
                    contact: document.getElementById('userContact').value.trim(),
                    login: document.getElementById('userLogin').value.trim(),
                    password: document.getElementById('userPassword').value
                };

                if (!payload.statut) {
                    alert('Sélectionnez un statut.');
                    return;
                }
                if (!payload.login) {
                    alert('Saisissez un login.');
                    return;
                }
                if (!payload.password || payload.password.length < 8) {
                    alert('Saisissez un mot de passe (8 caractères minimum).');
                    return;
                }

                if (editMode) {
                    var id = document.getElementById('userEditId').value;
                    UsersStore.updateUser(id, payload);
                } else {
                    UsersStore.addUser(payload);
                }
                renderUsers();
                closeModal();
            } catch (err) {
                alert(err.message || 'Erreur de validation');
            }
        });

        if (window.TableActions) {
            TableActions.setHandlers({
                view: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var u = UsersStore.getUser(id);
                    if (!u) return;
                    alert(
                        'ID : ' + (u.idCode || '') + '\n' +
                        'Date : ' + (u.date || '') + '\n' +
                        'Nom : ' + (u.nomComplet || '') + '\n' +
                        'Statut : ' + (u.statut || '') + '\n' +
                        'Contact : ' + (u.contact || '') + '\n' +
                        'Login : ' + (u.login || '')
                    );
                },
                edit: function (tr) {
                    var u = UsersStore.getUser(tr.getAttribute('data-id'));
                    if (u) openModal(u);
                },
                delete: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var u = UsersStore.getUser(id);
                    if (!confirm('Supprimer l’utilisateur ' + (u ? u.nomComplet : '') + ' ?')) return;
                    UsersStore.deleteUser(id);
                    renderUsers();
                },
                suspend: function (tr) {
                    var id = tr.getAttribute('data-id');
                    var u = UsersStore.suspendUser(id);
                    if (u) {
                        alert(u.statut === 'Suspendu'
                            ? 'Utilisateur suspendu.'
                            : 'Utilisateur réactivé.');
                    }
                    renderUsers();
                }
            });
            TableActions.bind(document.getElementById('usersBody'));
        }

        renderUsers();

        (function () {
            var input = document.getElementById('userPassword');
            var btn = document.getElementById('toggleUserPassword');
            if (!input || !btn) return;
            btn.addEventListener('click', function () {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.classList.toggle('is-visible', show);
                btn.setAttribute('aria-label', show ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
            });
        })();
    </script>
</body>
</html>
